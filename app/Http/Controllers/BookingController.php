<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        DB::beginTransaction();
        try {
            $schedule = Schedule::with(['price', 'film'])->findOrFail($request->schedule_id);
            $seatIds = $request->seat_ids;

            $unavailable = DB::table('seats')
                ->whereIn('id', $seatIds)
                ->where('status', '!=', 'available')
                ->count();

            if ($unavailable > 0) {
                return response()->json(['success' => false, 'message' => 'Beberapa kursi sudah tidak tersedia']);
            }

            $totalAmount = $schedule->price->amount * count($seatIds);
            $orderId = 'ORDER-' . time() . '-' . Auth::id();

            // Get seat codes
            $seats = DB::table('seats')->whereIn('id', $seatIds)->pluck('seat_code')->toArray();
            
            // Create booking for transaction history
            $booking = \App\Models\Booking::create([
                'user_id' => Auth::id(),
                'schedule_id' => $schedule->id,
                'seats' => $seats,
                'total_price' => $totalAmount,
                'status' => 'pending',
                'booking_code' => 'BK' . str_pad(Auth::id(), 3, '0', STR_PAD_LEFT) . time(),
            ]);

            $expiredAt = now()->addMinute();
            foreach ($seatIds as $seatId) {
                DB::table('seats')->where('id', $seatId)->update([
                    'status' => 'pending',
                    'user_id' => Auth::id(),
                    'reserved_until' => $expiredAt
                ]);
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'schedule_id' => $schedule->id,
                'order_time' => now(),
                'status' => 'pending',
            ]);

            foreach ($seatIds as $seatId) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'seat_id' => $seatId
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'total_amount' => $totalAmount,
                'payment_method' => 'midtrans',
                'status' => 'pending'
            ]);

            DB::commit();

            return $this->createPayment($order, $totalAmount, $schedule, $seatIds);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    protected function createPayment($order, $totalAmount, $schedule, $seatIds)
    {
        try {
            $params = [
                'transaction_details' => [
                    'order_id' => 'ORD-' . date('YmdHis') . '-' . $order->id . '-' . rand(1000, 9999),
                    'gross_amount' => (int)$totalAmount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone ?? '08123456789',
                ],
                'item_details' => [
                    [
                        'id' => 'ticket',
                        'price' => (int)$schedule->price->amount,
                        'quantity' => count($seatIds),
                        'name' => 'Tiket Bioskop - ' . $schedule->film->title
                    ]
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $order->id,
                'total_amount' => $totalAmount,
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment creation error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['status' => 'invalid signature'], 403);
        }

        if ($request->transaction_status == 'settlement') {
            // Extract order ID from transaction order_id (format: ORD-YmdHis-{order_id}-{random})
            $parts = explode('-', $request->order_id);
            $orderId = isset($parts[2]) ? $parts[2] : null;
            
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    Payment::where('order_id', $order->id)
                        ->update(['status' => 'completed', 'payment_time' => now()]);

                    $order->update(['status' => 'confirmed']);
                    
                    // Update booking status for transaction history
                    $booking = \App\Models\Booking::where('user_id', $order->user_id)
                        ->where('schedule_id', $order->schedule_id)
                        ->where('status', 'pending')
                        ->whereBetween('created_at', [
                            $order->order_time->subMinutes(2),
                            $order->order_time->addMinutes(2)
                        ])
                        ->first();
                        
                    if ($booking) {
                        $booking->update([
                            'status' => 'confirmed',
                            'payment_date' => now(),
                            'payment_method' => 'midtrans'
                        ]);
                    }

                    $details = OrderDetail::where('order_id', $order->id)->get();
                    foreach ($details as $detail) {
                        DB::table('seats')->where('id', $detail->seat_id)->update([
                            'status' => 'booked',
                            'reserved_until' => null
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
    
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);
        
        DB::beginTransaction();
        try {
            $order = Order::findOrFail($request->order_id);
            
            // Update order status
            $order->update(['status' => 'confirmed']);
            
            // Update payment status
            Payment::where('order_id', $order->id)
                ->update(['status' => 'completed', 'payment_time' => now()]);
            
            // Update booking status for transaction history
            // Find booking created around the same time as the order
            $booking = \App\Models\Booking::where('user_id', $order->user_id)
                ->where('schedule_id', $order->schedule_id)
                ->where('status', 'pending')
                ->whereBetween('created_at', [
                    $order->order_time->subMinutes(2),
                    $order->order_time->addMinutes(2)
                ])
                ->first();
                
            if ($booking) {
                $booking->update([
                    'status' => 'confirmed',
                    'payment_date' => now(),
                    'payment_method' => 'midtrans'
                ]);
            }
            
            // Update seats status
            $details = OrderDetail::where('order_id', $order->id)->get();
            foreach ($details as $detail) {
                DB::table('seats')->where('id', $detail->seat_id)->update([
                    'status' => 'booked',
                    'reserved_until' => null
                ]);
            }
            
            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

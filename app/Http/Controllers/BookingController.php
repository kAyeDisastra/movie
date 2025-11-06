<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id'
        ]);
        
        DB::beginTransaction();
        try {
            $schedule = Schedule::with(['price', 'film'])->findOrFail($request->schedule_id);
            $seatIds = $request->seat_ids;
            
            $unavailableSeats = DB::table('seats')
                ->whereIn('id', $seatIds)
                ->where('status', '!=', 'available')
                ->count();
                
            if ($unavailableSeats > 0) {
                return response()->json(['success' => false, 'message' => 'Beberapa kursi sudah tidak tersedia']);
            }
            
            $totalAmount = $schedule->price->amount * count($seatIds);
            $orderId = 'ORDER-' . time() . '-' . Auth::id();
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'schedule_id' => $schedule->id,
                'order_time' => now(),
                'status' => 'pending',
                'order_id' => $orderId
            ]);
            
            // Set seats to pending for 1 hour
            $expiredAt = now()->addHour();
            foreach ($seatIds as $seatId) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'seat_id' => $seatId
                ]);
                
                DB::table('seats')->where('id', $seatId)->update([
                    'status' => 'pending',
                    'user_id' => Auth::id(),
                    'expired_at' => $expiredAt
                ]);
            }
            
            Payment::create([
                'order_id' => $order->id,
                'total_amount' => $totalAmount,
                'payment_method' => 'midtrans',
                'status' => 'pending'
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'order_id' => $orderId,
                'total_amount' => $totalAmount,
                'price_per_seat' => $schedule->price->amount,
                'film_title' => $schedule->film->title,
                'seat_ids' => $seatIds
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal membeli tiket: ' . $e->getMessage()]);
        }
    }
    
    public function confirmPayment(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = Order::where('order_id', $request->order_id)->first();
            
            if ($order) {
                $orderDetails = OrderDetail::where('order_id', $order->id)->get();
                foreach ($orderDetails as $detail) {
                    DB::table('seats')->where('id', $detail->seat_id)->update([
                        'status' => 'booked',
                        'expired_at' => null
                    ]);
                }
                
                $order->update(['status' => 'confirmed']);
                
                Payment::where('order_id', $order->id)
                    ->update(['status' => 'completed', 'payment_time' => now()]);
            }
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
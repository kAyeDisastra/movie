<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function show($id)
    {
        $booking = Booking::with(['schedule.film', 'schedule.studio', 'user'])->find($id);
        
        if (!$booking || $booking->user_id !== auth()->id()) {
            return redirect()->route('transactions')->with('error', 'Booking tidak ditemukan');
        }
        
        // Check if booking expired (1 minute)
        if ($booking->created_at->addMinute()->isPast() && $booking->status === 'pending') {
            $booking->update(['status' => 'expired']);
            $this->releaseSeat($booking);
            return redirect()->route('transactions')->with('error', 'Booking sudah kadaluarsa');
        }
        
        if ($booking->status !== 'pending') {
            return redirect()->route('transactions')->with('error', 'Booking sudah dibayar atau dibatalkan');
        }
        
        $params = [
            'transaction_details' => [
                'order_id' => 'PAY-' . date('YmdHis') . '-' . $booking->id . '-' . rand(1000, 9999),
                'gross_amount' => $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
            ],
            'item_details' => [
                [
                    'id' => 'ticket-' . $booking->id,
                    'price' => $booking->total_price,
                    'quantity' => 1,
                    'name' => 'Tiket Film: ' . ($booking->schedule->film->title ?? 'Film'),
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return view('payment', compact('booking', 'snapToken'));
        } catch (\Exception $e) {
            return redirect()->route('transactions')->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }
    
    private function releaseSeat($booking)
    {
        \App\Models\Seat::where('schedule_id', $booking->schedule_id)
            ->where('user_id', $booking->user_id)
            ->whereIn('seat_code', $booking->seats)
            ->update(['status' => 'available', 'user_id' => null, 'reserved_until' => null]);
    }

    public function callback(Request $request)
    {
        $notification = new Notification();
        
        $transaction = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;
        
        // Extract booking ID from order_id
        $bookingId = explode('-', $orderId)[1];
        $booking = Booking::find($bookingId);
        
        if ($booking) {
            if ($transaction == 'capture' || $transaction == 'settlement') {
                $booking->status = 'confirmed';
                $booking->payment_date = now();
                $booking->save();
            } elseif ($transaction == 'cancel' || $transaction == 'deny' || $transaction == 'expire') {
                $booking->status = 'expired';
                $booking->save();
                $this->releaseSeat($booking);
            }
        }
        
        return response()->json(['status' => 'success']);
    }
    
    public function getToken($id)
    {
        $booking = Booking::with(['schedule.film', 'schedule.studio', 'user'])->find($id);
        
        if (!$booking || $booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Booking tidak ditemukan'], 404);
        }
        
        if ($booking->created_at->addMinute()->isPast() && $booking->status === 'pending') {
            $booking->update(['status' => 'expired']);
            $this->releaseSeat($booking);
            return response()->json(['error' => 'Booking sudah kadaluarsa'], 400);
        }
        
        if ($booking->status !== 'pending') {
            return response()->json(['error' => 'Booking sudah dibayar atau dibatalkan'], 400);
        }
        
        $params = [
            'transaction_details' => [
                'order_id' => 'TXN-' . date('YmdHis') . '-' . $booking->id . '-' . rand(1000, 9999),
                'gross_amount' => $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
            ],
            'item_details' => [
                [
                    'id' => 'ticket-' . $booking->id,
                    'price' => $booking->total_price,
                    'quantity' => 1,
                    'name' => 'Tiket Film: ' . ($booking->schedule->film->title ?? 'Film'),
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal membuat token: ' . $e->getMessage()], 500);
        }
    }
}
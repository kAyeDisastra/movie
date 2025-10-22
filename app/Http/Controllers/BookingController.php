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
        $studioId = $request->studio_id;
        $seats = $request->seats;
        
        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'schedule_id' => $request->schedule_id ?? 1,
                'order_time' => now(),
                'status' => 'pending',
                'created_at' => now()
            ]);
            
            // Create order details for each seat
            foreach ($seats as $seatId) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'seat_id' => $seatId
                ]);
            }
            
            // Create payment
            Payment::create([
                'order_id' => $order->id,
                'total_amount' => count($seats) * 50000,
                'payment_method' => 'cash',
                'status' => 'completed',
                'payment_time' => now()
            ]);
            
            // Update order status
            $order->update(['status' => 'confirmed']);
            
            // Store booked seats in session
            $bookedSeats = session()->get("booked_seats_studio_{$studioId}", []);
            $bookedSeats = array_merge($bookedSeats, $seats);
            session()->put("booked_seats_studio_{$studioId}", array_unique($bookedSeats));
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tiket berhasil dibeli!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal membeli tiket']);
        }
    }
}
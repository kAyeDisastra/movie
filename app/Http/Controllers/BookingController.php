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
                'total_amount' => count($seats) * 50000, // Rp 50,000 per seat
                'status' => 'pending'
            ]);
            
            // Create order details for each seat
            foreach ($seats as $seat) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'schedule_id' => 1, // Demo schedule ID
                    'seat_code' => (string)$seat,
                    'price' => 50000
                ]);
            }
            
            // Create payment
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_method' => 'cash',
                'status' => 'completed'
            ]);
            
            // Update order status
            $order->update(['status' => 'completed']);
            
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
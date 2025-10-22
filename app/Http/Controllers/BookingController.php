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
            $schedule = Schedule::with('price')->findOrFail($request->schedule_id);
            $seatIds = $request->seat_ids;
            
            $unavailableSeats = DB::table('seats')
                ->whereIn('id', $seatIds)
                ->where('status', '!=', 'available')
                ->count();
                
            if ($unavailableSeats > 0) {
                return response()->json(['success' => false, 'message' => 'Beberapa kursi sudah tidak tersedia']);
            }
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'schedule_id' => $schedule->id,
                'order_time' => now(),
                'status' => 'pending',
                'created_at' => now()
            ]);
            
            foreach ($seatIds as $seatId) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'seat_id' => $seatId
                ]);
                
                DB::table('seats')->where('id', $seatId)->update(['status' => 'booked']);
            }
            
            $totalAmount = $schedule->price->amount * count($seatIds);
            
            Payment::create([
                'order_id' => $order->id,
                'total_amount' => $totalAmount,
                'payment_method' => 'cash',
                'status' => 'completed',
                'payment_time' => now()
            ]);
            
            $order->update(['status' => 'confirmed']);
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tiket berhasil dibeli!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal membeli tiket: ' . $e->getMessage()]);
        }
    }
}
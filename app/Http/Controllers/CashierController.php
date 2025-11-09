<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Studio;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function dashboard(Request $request)
    {
        // Auto cleanup expired seats and bookings
        \DB::table('seats')
            ->where('status', 'pending')
            ->where('reserved_until', '<', now())
            ->update([
                'status' => 'available',
                'user_id' => null,
                'reserved_until' => null
            ]);
            
        $expiredBookings = \App\Models\Booking::where('status', 'pending')
            ->where('created_at', '<', now()->subMinute())
            ->get();
            
        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'expired']);
        }
        
        $studios = Studio::with(['schedules' => function($query) {
            $query->with('film')
                  ->whereDate('show_date', '>=', today())
                  ->orderBy('show_date')
                  ->orderBy('show_time');
        }])->get();
        
        return view('cashier.dashboard', compact('studios'));
    }
    
    public function showSeats($scheduleId)
    {
        // Auto cleanup expired seats
        \DB::table('seats')
            ->where('status', 'pending')
            ->where('reserved_until', '<', now())
            ->update([
                'status' => 'available',
                'user_id' => null,
                'reserved_until' => null
            ]);
            
        // Auto expire bookings
        $expiredBookings = \App\Models\Booking::where('status', 'pending')
            ->where('created_at', '<', now()->subMinute())
            ->get();
            
        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'expired']);
        }
        
        $schedule = Schedule::with(['film', 'studio', 'price'])->findOrFail($scheduleId);
        $seats = Seat::where('schedule_id', $scheduleId)->get();
        
        return view('cashier.seats', compact('schedule', 'seats'));
    }
    
    public function bookOffline(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_ids' => 'required|string',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|numeric|digits_between:9,12'
        ]);
        
        // Add +62 prefix to phone number
        $phoneNumber = '+62' . $request->customer_phone;
        
        $seatIds = explode(',', $request->seat_ids);

        DB::beginTransaction();
        try {
            $schedule = Schedule::with('price')->findOrFail($request->schedule_id);
            
            // Check seat availability
            $unavailable = Seat::whereIn('id', $seatIds)
                ->where('status', '!=', 'available')
                ->count();
                
            if ($unavailable > 0) {
                return back()->with('error', 'Beberapa kursi sudah tidak tersedia');
            }
            
            $seats = Seat::whereIn('id', $seatIds)->pluck('seat_code')->toArray();
            $totalPrice = $schedule->price->amount * count($seatIds);
            
            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'schedule_id' => $schedule->id,
                'seats' => $seats,
                'total_price' => $totalPrice,
                'status' => 'confirmed',
                'booking_code' => 'CS' . str_pad(auth()->id(), 3, '0', STR_PAD_LEFT) . time(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $phoneNumber,
                'payment_method' => 'cash',
                'payment_date' => now(),
            ]);
            
            // Update seats
            Seat::whereIn('id', $seatIds)->update([
                'status' => 'booked',
                'user_id' => auth()->id(),
                'reserved_until' => null
            ]);
            
            DB::commit();
            
            return redirect()->route('cashier.dashboard')->with('success', 'Booking berhasil! Kode: ' . $booking->booking_code . ' - Customer: ' . $request->customer_name);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
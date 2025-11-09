<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\Schedule;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function index()
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
        
        return view('studios', compact('studios'));
    }

    public function show($id, Request $request)
    {
        $studio = Studio::findOrFail($id);
        $scheduleId = $request->get('schedule_id');

        if ($scheduleId) {
            $schedule = Schedule::where('id', $scheduleId)
                ->where('studio_id', $id)
                ->with(['seats', 'film', 'price'])
                ->first();
        } else {
            $schedule = Schedule::where('studio_id', $id)
                ->where('show_date', '>=', today())
                ->with(['seats', 'film', 'price'])
                ->first();
        }

        if (!$schedule) {
            return redirect()->route('studios')->with('error', 'Tidak ada jadwal untuk studio ini');
        }

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

        $seats = $schedule->seats()->get();
        $bookedSeatIds = $seats->where('status', 'booked')->pluck('id')->toArray();
        $pendingSeatIds = $seats->where('status', 'pending')->pluck('id')->toArray();

        return view('studio-detail', compact('studio', 'seats', 'bookedSeatIds', 'pendingSeatIds', 'schedule'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\Schedule;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function index()
    {
        $data['studios'] = Studio::all();
        
        return view('studios', $data);
    }

    public function show($id, Request $request)
    {
        $studio = Studio::findOrFail($id);
        
        // Get schedule ID from request or find first available schedule
        $scheduleId = $request->get('schedule_id');
        
        if ($scheduleId) {
            $schedule = Schedule::where('id', $scheduleId)
                ->where('studio_id', $id)
                ->with(['seats', 'film', 'price'])
                ->first();
        } else {
            // Get any available schedule for this studio
            $schedule = Schedule::where('studio_id', $id)
                ->where('show_date', '>=', today())
                ->with(['seats', 'film', 'price'])
                ->first();
        }
            
        if (!$schedule) {
            return redirect()->route('studios')->with('error', 'Tidak ada jadwal untuk studio ini');
        }
        
        $seats = $schedule->seats;
        $bookedSeatIds = $seats->where('status', '!=', 'available')->pluck('id')->toArray();
        
        return view('studio-detail', compact('studio', 'seats', 'bookedSeatIds', 'schedule'));
    }
}
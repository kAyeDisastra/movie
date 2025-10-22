<?php

namespace App\Http\Controllers;

use App\Models\Studio;

class StudioController extends Controller
{
    public function index()
    {
        $data['studios'] = Studio::all();
        
        return view('studios', $data);
    }

    public function show($id)
    {
        $data['studio'] = Studio::findOrFail($id);
        $data['seats'] = range(1, 35);
        
        // Get booked seats from session
        $bookedSeats = session()->get("booked_seats_studio_{$id}", [3, 7, 15, 22, 28]);
        
        $data['bookedSeats'] = $bookedSeats;
        
        return view('studio-detail', $data);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $films = Film::with(['schedules.studio', 'schedules.price'])
            ->whereHas('schedules', function($query) {
                $query->where('show_date', '>=', today());
            })
            ->where('status', 'play_now')
            ->get();
   
        return view('dashboard', compact('films'));
    }

    public function show($id)
    {
        $film = Film::with(['schedules.studio', 'schedules.price'])
            ->findOrFail($id);
        return view('film-detail', compact('film'));
    }

    public function schedules($id)
    {
        $film = Film::with(['schedules.studio', 'schedules.price'])
            ->findOrFail($id);
        return view('schedules', compact('film'));
    }
}
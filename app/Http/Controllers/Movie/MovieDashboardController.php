<?php

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $data['films'] = Film::where('status', 'play_now')
            ->whereHas('schedules', function ($query) use ($today) {
                $query->whereDate('show_date', $today);
            })
            ->with(['schedules' => function ($query) use ($today) {
                $query->whereDate('show_date', $today);
            }])
            ->get();

        return view('movies.dashboard', $data);
    }
}

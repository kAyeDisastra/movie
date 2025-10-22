<?php

namespace App\Http\Controllers;

use App\Models\Film;

class UpcomingMovieController extends Controller
{
    public function index()
    {
        $data['films'] = Film::where('status', 'coming_soon')->get();
        
        return view('upcoming-movies', $data);
    }
}
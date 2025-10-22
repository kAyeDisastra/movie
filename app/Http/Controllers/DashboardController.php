<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $films = Film::with(['schedules.studio'])->get();
   
        return view('dashboard', compact('films'));
    }

    public function show($id)
    {
        $film = Film::findOrFail($id);
        return view('film-detail', compact('film'));
    }
}
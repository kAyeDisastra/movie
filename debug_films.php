<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Film;
use App\Models\Schedule;
use Carbon\Carbon;

echo "=== DEBUG FILM NOW PLAYING ===\n\n";

// 1. Cek semua film di database
echo "1. Semua film di database:\n";
$allFilms = Film::all();
foreach ($allFilms as $film) {
    echo "- ID: {$film->id}, Title: {$film->title}, Status: {$film->status}\n";
}

echo "\n2. Film dengan status 'play_now':\n";
$playNowFilms = Film::where('status', 'play_now')->get();
foreach ($playNowFilms as $film) {
    echo "- ID: {$film->id}, Title: {$film->title}\n";
}

echo "\n3. Semua jadwal:\n";
$schedules = Schedule::with(['film', 'studio'])->get();
foreach ($schedules as $schedule) {
    echo "- Film: {$schedule->film->title}, Studio: {$schedule->studio->name}, Date: {$schedule->show_date}, Time: {$schedule->show_time}\n";
}

echo "\n4. Jadwal hari ini dan ke depan:\n";
$todaySchedules = Schedule::with(['film', 'studio'])
    ->where('show_date', '>=', today())
    ->get();
foreach ($todaySchedules as $schedule) {
    echo "- Film: {$schedule->film->title}, Studio: {$schedule->studio->name}, Date: {$schedule->show_date}, Time: {$schedule->show_time}\n";
}

echo "\n5. Query yang sama dengan DashboardController:\n";
$films = Film::with(['schedules.studio', 'schedules.price'])
    ->whereHas('schedules', function($query) {
        $query->where('show_date', '>=', today());
    })
    ->where('status', 'play_now')
    ->get();

echo "Jumlah film yang ditemukan: " . $films->count() . "\n";
foreach ($films as $film) {
    echo "- {$film->title} (Jadwal: {$film->schedules->count()})\n";
}

echo "\n6. Tanggal hari ini: " . today()->format('Y-m-d') . "\n";
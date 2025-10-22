<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Film;
use App\Models\Studio;
use App\Models\Price;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $films = Film::all();
        $studios = Studio::all();
        $prices = Price::all();

        $times = ['10:00', '13:00', '16:00', '19:00', '21:30'];
        $dates = [
            Carbon::today(),
            Carbon::today()->addDay(),
            Carbon::today()->addDays(2),
        ];

        $scheduleData = [];
        $studioIndex = 0;
        $filmIndex = 0;
        
        foreach ($dates as $date) {
            foreach ($times as $time) {
                $scheduleData[] = [
                    'film_id' => $films[$filmIndex % $films->count()]->id,
                    'studio_id' => $studios[$studioIndex % $studios->count()]->id,
                    'show_date' => $date,
                    'show_time' => $time,
                    'price_id' => $prices->random()->id,
                    'created_by' => $admin->id,
                ];
                $studioIndex++;
                $filmIndex++;
            }
        }
        Schedule::insert($scheduleData);
    }
}
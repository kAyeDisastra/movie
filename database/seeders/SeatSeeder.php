<?php

namespace Database\Seeders;

use App\Models\Seat;
use App\Models\Schedule;
use App\Models\Studio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = Schedule::all();

        foreach ($schedules as $schedule) {
            $studio = DB::table('studios')->where('id', $schedule->studio_id)->first();
            $capacity = $studio->capacity;
            $rows = ceil($capacity / 10);
            $seatData = [];
            
            for ($row = 1; $row <= $rows; $row++) {
                $seatsInRow = min(10, $capacity - (($row - 1) * 10));
                
                for ($seat = 1; $seat <= $seatsInRow; $seat++) {
                    $seatCode = chr(64 + $row) . $seat;
                    
                    $seatData[] = [
                        'schedule_id' => $schedule->id,
                        'seat_code' => $seatCode,
                        'status' => 'available',
                    ];
                }
            }
            
            if (!empty($seatData)) {
                Seat::insert($seatData);
            }
        }
    }
}
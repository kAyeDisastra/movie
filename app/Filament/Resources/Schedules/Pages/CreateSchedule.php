<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\ScheduleResource;
use App\Models\Seat;
use App\Models\Studio;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;
    
    protected function afterCreate(): void
    {
        $schedule = $this->record;
        $studio = Studio::find($schedule->studio_id);
        
        if ($studio) {
            // Check if seats already exist for this schedule
            $existingSeats = Seat::where('schedule_id', $schedule->id)->count();
            
            if ($existingSeats == 0) {
                // Create seats based on studio capacity
                $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                $seatsPerRow = ceil($studio->capacity / count($rows));
                $seatNumber = 1;
                
                for ($i = 0; $i < count($rows) && $seatNumber <= $studio->capacity; $i++) {
                    for ($j = 1; $j <= $seatsPerRow && $seatNumber <= $studio->capacity; $j++) {
                        Seat::create([
                            'schedule_id' => $schedule->id,
                            'seat_code' => $rows[$i] . $j,
                            'status' => 'available'
                        ]);
                        $seatNumber++;
                    }
                }
            }
        }
    }
}

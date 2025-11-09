<?php

namespace App\Observers;

use App\Models\Studio;
use App\Models\Seat;

class StudioObserver
{
    public function updated(Studio $studio)
    {
        // Check if capacity was changed
        if ($studio->isDirty('capacity')) {
            $newCapacity = $studio->capacity;
            
            // Update seats for all schedules in this studio
            foreach ($studio->schedules as $schedule) {
                $currentSeats = Seat::where('schedule_id', $schedule->id)->count();
                
                if ($currentSeats < $newCapacity) {
                    // Add more seats
                    $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                    $seatsPerRow = ceil($newCapacity / count($rows));
                    
                    // Get existing seat codes to avoid duplicates
                    $existingCodes = Seat::where('schedule_id', $schedule->id)
                        ->pluck('seat_code')
                        ->toArray();
                    
                    $seatNumber = $currentSeats + 1;
                    
                    for ($i = 0; $i < count($rows) && $seatNumber <= $newCapacity; $i++) {
                        for ($j = 1; $j <= $seatsPerRow && $seatNumber <= $newCapacity; $j++) {
                            $seatCode = $rows[$i] . $j;
                            
                            if (!in_array($seatCode, $existingCodes)) {
                                Seat::create([
                                    'schedule_id' => $schedule->id,
                                    'seat_code' => $seatCode,
                                    'status' => 'available'
                                ]);
                                $seatNumber++;
                            }
                        }
                    }
                } elseif ($currentSeats > $newCapacity) {
                    // Remove excess seats (only available ones)
                    $excessSeats = $currentSeats - $newCapacity;
                    Seat::where('schedule_id', $schedule->id)
                        ->where('status', 'available')
                        ->orderBy('id', 'desc')
                        ->limit($excessSeats)
                        ->delete();
                }
            }
        }
    }
}
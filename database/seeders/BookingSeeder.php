<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Get customer from UserSeeder
        $customer = User::where('email', 'customer@example.com')->first();
        $schedules = Schedule::with('film')->take(3)->get();

        if ($customer && $schedules->count() > 0) {
            foreach ($schedules as $index => $schedule) {
                $bookingCode = 'BK' . str_pad($customer->id, 3, '0', STR_PAD_LEFT) . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                
                Booking::updateOrCreate(
                    [
                        'booking_code' => $bookingCode,
                    ],
                    [
                        'user_id' => $customer->id,
                        'schedule_id' => $schedule->id,
                        'seats' => ['A1', 'A2'],
                        'total_price' => 50000 + ($index * 10000),
                        'status' => $index === 0 ? 'confirmed' : 'pending',
                        'payment_date' => $index === 0 ? now() : null,
                    ]
                );
            }
        }
    }
}
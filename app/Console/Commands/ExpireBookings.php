<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    protected $signature = 'bookings:expire';
    protected $description = 'Expire bookings older than 15 minutes';

    public function handle()
    {
        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', now()->subMinute())
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'expired']);
            
            Seat::where('schedule_id', $booking->schedule_id)
                ->where('user_id', $booking->user_id)
                ->whereIn('seat_code', $booking->seats)
                ->update(['status' => 'available', 'user_id' => null, 'reserved_until' => null]);
        }

        $this->info("Expired {$expiredBookings->count()} bookings");
    }
}
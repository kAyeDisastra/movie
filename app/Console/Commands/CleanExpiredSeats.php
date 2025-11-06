<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanExpiredSeats extends Command
{
    protected $signature = 'seats:clean-expired';
    protected $description = 'Clean expired pending seats';

    public function handle()
    {
        $cleaned = DB::table('seats')
            ->where('status', 'pending')
            ->where('expired_at', '<', now())
            ->update([
                'status' => 'available',
                'user_id' => null,
                'expired_at' => null
            ]);

        $this->info("Cleaned {$cleaned} expired seats");
        return 0;
    }
}

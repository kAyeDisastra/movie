<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FilmSeeder::class,
            StudioSeeder::class,
            PriceSeeder::class,
            ScheduleSeeder::class,
            SeatSeeder::class,
            BookingSeeder::class,
//            OrderSeeder::class,
//            PaymentSeeder::class,
//            InvoiceSeeder::class,
//            ReportSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('role', 'customer')->first();
        $cashier = User::where('role', 'cashier')->first();
        $schedules = Schedule::take(3)->get();

        foreach ($schedules as $schedule) {
            $orderId = Order::insertGetId([
                'user_id' => $customer->id,
                'schedule_id' => $schedule->id,
                'order_time' => Carbon::now()->subHours(rand(1, 24)),
                'status' => 'confirmed',
                'cashier_id' => $cashier->id,
                'created_at' => Carbon::now(),
            ]);
            
            $order = Order::find($orderId);

            $seats = Seat::where('schedule_id', $schedule->id)
                         ->where('status', 'available')
                         ->take(rand(1, 3))
                         ->get();

            foreach ($seats as $seat) {
                OrderDetail::insert([
                    'order_id' => $order->id,
                    'seat_id' => $seat->id,
                ]);

                Seat::where('id', $seat->id)->update(['status' => 'booked']);
            }
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Price;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        $paymentData = [];
        foreach ($orders as $order) {
            $seatCount = DB::table('order_details')->where('order_id', $order->id)->count();
            $schedule = DB::table('schedules')->where('id', $order->schedule_id)->first();
            $price = DB::table('prices')->where('id', $schedule->price_id)->first();
            $totalAmount = $price->amount * $seatCount;

            $paymentData[] = [
                'order_id' => $order->id,
                'payment_method' => collect(['Cash', 'Credit Card', 'Debit Card', 'E-Wallet'])->random(),
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'payment_time' => Carbon::now()->subHours(rand(1, 12)),
            ];
        }
        
        if (!empty($paymentData)) {
            Payment::insert($paymentData);
        }
    }
}
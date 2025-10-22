<?php

namespace Database\Seeders;

use App\Models\Price;
use App\Models\User;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        Price::insert([
            [
                'type' => 'Regular',
                'amount' => 35000.00,
                'created_by' => $admin->id,
            ],
            [
                'type' => 'Weekend',
                'amount' => 45000.00,
                'created_by' => $admin->id,
            ],
            [
                'type' => 'Premium',
                'amount' => 55000.00,
                'created_by' => $admin->id,
            ],
        ]);
    }
}
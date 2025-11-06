<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'phone' => '081234567890',
                'address' => 'Jl. Admin No. 1',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Owner',
                'password' => bcrypt('password'),
                'phone' => '081234567891',
                'address' => 'Jl. Owner No. 1',
                'role' => 'owner',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cashier@example.com'],
            [
                'name' => 'Kasir 1',
                'password' => bcrypt('password'),
                'phone' => '081234567892',
                'address' => 'Jl. Kasir No. 1',
                'role' => 'cashier',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer 1',
                'password' => bcrypt('password'),
                'phone' => '081234567893',
                'address' => 'Jl. Customer No. 1',
                'role' => 'customer',
            ]
        );
    }
}
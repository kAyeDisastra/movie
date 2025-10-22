<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
            'phone' => '081234567891',
            'address' => 'Jl. Owner No. 1',
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => 'cashier@example.com',
            'password' => bcrypt('password'),
            'phone' => '081234567892',
            'address' => 'Jl. Kasir No. 1',
            'role' => 'cashier',
        ]);

        User::create([
            'name' => 'Customer 1',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'phone' => '081234567893',
            'address' => 'Jl. Customer No. 1',
            'role' => 'customer',
        ]);
    }
}
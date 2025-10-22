<?php

namespace Database\Seeders;

use App\Models\Studio;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudioSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        Studio::insert([
            [
                'name' => 'Studio A',
                'capacity' => 50,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Studio B',
                'capacity' => 75,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Studio C',
                'capacity' => 100,
                'created_by' => $admin->id,
            ],
        ]);
    }
}
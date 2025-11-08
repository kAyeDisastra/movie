<?php

namespace Database\Seeders;

use App\Models\Film;
use App\Models\User;
use Illuminate\Database\Seeder;

class FilmSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        Film::create([
            'title' => 'Avengers: Endgame',
            'genre' => [
                'Action',
                'Adventure',
                'Drama'
            ],
            'duration' => 181,
            'description' => 'The grave course of events set in motion by Thanos that wiped out half the universe.',
            'status' => 'play_now',
            'poster_image' => 'avengers-endgame.jpg',
            'created_by' => $admin->id,
        ]);

        Film::create([
            'title' => 'Spider-Man: No Way Home',
            'genre' => ['Action', 'Adventure', 'Sci-Fi'],
            'duration' => 148,
            'description' => 'With Spider-Man\'s identity now revealed, Peter asks Doctor Strange for help.',
            'status' => 'play_now',
            'poster_image' => 'spiderman-nwh.jpg',
            'created_by' => $admin->id,
        ]);

        Film::create([
            'title' => 'The Batman',
            'genre' => ['Action', 'Crime', 'Drama'],
            'duration' => 176,
            'description' => 'Batman ventures into Gotham City\'s underworld when a sadistic killer leaves behind a trail of cryptic clues.',
            'status' => 'coming_soon',
            'poster_image' => 'the-batman.jpg',
            'created_by' => $admin->id,
        ]);
    }
}

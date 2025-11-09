<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Film;
use Filament\Widgets\ChartWidget;

class FilmsChart extends ChartWidget
{
    protected ?string $heading = 'Films by Status';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $nowShowing = Film::where('status', 'now_showing')->count();
        $comingSoon = Film::where('status', 'coming_soon')->count();
        $ended = Film::where('status', 'ended')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Films',
                    'data' => [$nowShowing, $comingSoon, $ended],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(251, 191, 36)', 
                        'rgb(239, 68, 68)',
                    ],
                ],
            ],
            'labels' => ['Now Showing', 'Coming Soon', 'Ended'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Film;
use App\Models\Schedule;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Films', Film::count())
                ->description('Total films in database')
                ->descriptionIcon('heroicon-m-film')
                ->color('success'),
            
            Stat::make('Active Films', Film::where('status', 'now_showing')->count())
                ->description('Currently showing')
                ->descriptionIcon('heroicon-m-play')
                ->color('primary'),
            
            Stat::make('Coming Soon', Film::where('status', 'coming_soon')->count())
                ->description('Upcoming releases')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),
            
            Stat::make('Total Schedules', Schedule::count())
                ->description('All movie schedules')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];
    }
}
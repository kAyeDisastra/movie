<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $dailyRevenue = Booking::where('status', 'confirmed')
            ->whereDate('created_at', $today)
            ->sum('total_price');

        $monthlyRevenue = Booking::where('status', 'confirmed')
            ->where('created_at', '>=', $thisMonth)
            ->sum('total_price');

        $lastMonthRevenue = Booking::where('status', 'confirmed')
            ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->sum('total_price');

        $totalTickets = Booking::where('status', 'confirmed')->count();

        $monthlyGrowth = $lastMonthRevenue > 0 
            ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        return [
            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($dailyRevenue, 0, ',', '.'))
                ->description('Total penjualan hari ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description(($monthlyGrowth >= 0 ? '+' : '') . number_format($monthlyGrowth, 1) . '% dari bulan lalu')
                ->descriptionIcon($monthlyGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthlyGrowth >= 0 ? 'success' : 'danger'),

            Stat::make('Total Tiket Terjual', number_format($totalTickets))
                ->description('Semua tiket yang terkonfirmasi')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('info'),
        ];
    }
}
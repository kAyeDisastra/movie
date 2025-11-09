<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $onlineRevenue = Booking::where('status', 'confirmed')
            ->where('payment_method', '!=', 'cash')
            ->sum('total_price');
            
        $offlineRevenue = Booking::where('status', 'confirmed')
            ->where('payment_method', 'cash')
            ->sum('total_price');
            
        $totalRevenue = $onlineRevenue + $offlineRevenue;
        
        $monthlyRevenue = Booking::where('status', 'confirmed')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total_price');
        
        return [
            Stat::make('Penghasilan Online', 'Rp ' . number_format($onlineRevenue, 0, ',', '.'))
                ->description('Pembayaran digital')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('success'),
            
            Stat::make('Penghasilan Offline', 'Rp ' . number_format($offlineRevenue, 0, ',', '.'))
                ->description('Pembayaran tunai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
            
            Stat::make('Penghasilan Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description(date('F Y'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
            
            Stat::make('Total Penghasilan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Semua transaksi')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),
        ];
    }
}
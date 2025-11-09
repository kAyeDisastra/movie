<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class YearlyRevenueChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    
    public function getHeading(): ?string
    {
        return 'Keuangan Tahunan';
    }

    protected function getData(): array
    {
        $data = Booking::where('status', 'confirmed')
            ->select(
                DB::raw('strftime("%m", created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $revenues = array_fill(0, 12, 0);

        foreach ($data as $item) {
            $revenues[$item->month - 1] = $item->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Penghasilan (Rp)',
                    'data' => $revenues,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
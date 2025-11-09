<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Booking;
use App\Models\Film;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopFilmsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    
    public function getHeading(): ?string
    {
        return 'Film Terlaris';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Film::query()
                    ->select('films.*')
                    ->selectRaw('COUNT(bookings.id) as total_bookings')
                    ->selectRaw('SUM(bookings.total_price) as total_revenue')
                    ->leftJoin('schedules', 'films.id', '=', 'schedules.film_id')
                    ->leftJoin('bookings', function($join) {
                        $join->on('schedules.id', '=', 'bookings.schedule_id')
                             ->where('bookings.status', '=', 'confirmed');
                    })
                    ->groupBy('films.id')
                    ->orderByDesc('total_bookings')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Film')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('genre')
                    ->label('Genre')
                    ->badge(),
                Tables\Columns\TextColumn::make('total_bookings')
                    ->label('Total Booking')
                    ->sortable()
                    ->default(0),
                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Total Pendapatan')
                    ->money('IDR')
                    ->sortable()
                    ->default(0),
            ])
            ->defaultSort('total_bookings', 'desc');
    }
}
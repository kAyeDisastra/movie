<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('film_id')
                    ->relationship('film', 'title')
                    ->required(),
                Select::make('studio_id')
                    ->relationship('studio', 'name')
                    ->required(),
                DatePicker::make('show_date')
                    ->required(),
                TimePicker::make('show_time')
                    ->required(),
                Select::make('price_id')
                    ->columnSpanFull()
                    ->prefix('Rp')
                    ->relationship('price', 'amount')
                    ->required(),
                Hidden::make('created_by')
                    ->default(Auth::user()->id)
            ]);
    }
}

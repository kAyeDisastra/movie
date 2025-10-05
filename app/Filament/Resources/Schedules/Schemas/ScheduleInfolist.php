<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('film_id')
                    ->numeric(),
                TextEntry::make('studio_id')
                    ->numeric(),
                TextEntry::make('show_date')
                    ->date(),
                TextEntry::make('show_time')
                    ->time(),
                TextEntry::make('price_id')
                    ->numeric(),
                TextEntry::make('created_by')
                    ->numeric(),
            ]);
    }
}

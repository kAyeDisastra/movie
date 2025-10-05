<?php

namespace App\Filament\Resources\Schedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('film.title')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('studio.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('show_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('show_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('price.amount')
                    ->prefix('Rp. ')
                    ->money('IDR')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

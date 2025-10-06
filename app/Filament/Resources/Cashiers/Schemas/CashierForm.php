<?php

namespace App\Filament\Resources\Cashiers\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CashierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->columnSpanFull()
                    ->hiddenOn('edit')
                    ->label('Password')
                    ->dehydrateStateUsing(fn($state) => bcrypt($state)),
                Hidden::make('role')
                    ->default('cashier')
            ]);
    }
}

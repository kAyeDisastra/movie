<?php

namespace App\Filament\Resources\Cashiers;

use App\Filament\Resources\Cashiers\Pages\CreateCashier;
use App\Filament\Resources\Cashiers\Pages\EditCashier;
use App\Filament\Resources\Cashiers\Pages\ListCashiers;
use App\Filament\Resources\Cashiers\Schemas\CashierForm;
use App\Filament\Resources\Cashiers\Tables\CashiersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CashierResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Cashier';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'cashier');
    }

    public static function form(Schema $schema): Schema
    {
        return CashierForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashiersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCashiers::route('/'),
            'create' => CreateCashier::route('/create'),
            'edit' => EditCashier::route('/{record}/edit'),
        ];
    }
}

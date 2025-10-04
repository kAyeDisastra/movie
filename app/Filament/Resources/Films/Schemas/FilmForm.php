<?php

namespace App\Filament\Resources\Films\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FilmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('genre')
                    ->required(),
                TextInput::make('duration')
                    ->required()
                    ->numeric(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['play_now' => 'Play now', 'coming_soon' => 'Coming soon', 'history' => 'History'])
                    ->default('coming_soon')
                    ->required(),
                FileUpload::make('poster_image')
                    ->image(),
            ]);
    }
}

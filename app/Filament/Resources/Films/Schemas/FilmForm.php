<?php

namespace App\Filament\Resources\Films\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class FilmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TagsInput::make('genre')
                    ->placeholder('')
                    ->required(),
                TextInput::make('duration')
                    ->label('Duration (Minutes)')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['play_now' => 'Play now', 'coming_soon' => 'Coming soon', 'history' => 'History'])
                    ->default('coming_soon')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('poster_image')
                    ->columnSpanFull()
                    ->image(),
                Hidden::make('created_by')
                    ->default(Auth::user()->id)
            ]);
    }
}

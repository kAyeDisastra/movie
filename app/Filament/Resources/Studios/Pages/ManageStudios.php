<?php

namespace App\Filament\Resources\Studios\Pages;

use App\Filament\Resources\Studios\StudioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStudios extends ManageRecords
{
    protected static string $resource = StudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

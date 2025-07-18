<?php

namespace App\Filament\Resources\TypeGalonResource\Pages;

use App\Filament\Resources\TypeGalonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTypeGalons extends ListRecords
{
    protected static string $resource = TypeGalonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

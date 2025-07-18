<?php

namespace App\Filament\Resources\TypeGalonResource\Pages;

use App\Filament\Resources\TypeGalonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeGalon extends EditRecord
{
    protected static string $resource = TypeGalonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

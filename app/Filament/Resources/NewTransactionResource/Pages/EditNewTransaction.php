<?php

namespace App\Filament\Resources\NewTransactionResource\Pages;

use App\Filament\Resources\NewTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewTransaction extends EditRecord
{
    protected static string $resource = NewTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

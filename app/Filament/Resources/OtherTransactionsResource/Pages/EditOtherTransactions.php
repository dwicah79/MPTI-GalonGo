<?php

namespace App\Filament\Resources\OtherTransactionsResource\Pages;

use App\Filament\Resources\OtherTransactionsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOtherTransactions extends EditRecord
{
    protected static string $resource = OtherTransactionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

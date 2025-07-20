<?php

namespace App\Filament\Resources\OtherTransactionsResource\Pages;

use App\Filament\Resources\OtherTransactionsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOtherTransactions extends ListRecords
{
    protected static string $resource = OtherTransactionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

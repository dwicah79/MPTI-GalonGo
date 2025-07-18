<?php

namespace App\Filament\Resources\NewTransactionResource\Pages;

use App\Filament\Resources\NewTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNewTransaction extends CreateRecord
{
    protected static string $resource = NewTransactionResource::class;
}

<?php

namespace App\Filament\Resources\NewTransactionResource\Pages;

use App\Filament\Resources\NewTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewTransactions extends ListRecords
{
    protected static string $resource = NewTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('laporan')
                ->label('Laporan Penjualan')
                ->url(LaporanPenjualan::getUrl())
                ->icon('heroicon-o-document-text'),
        ];
    }
}

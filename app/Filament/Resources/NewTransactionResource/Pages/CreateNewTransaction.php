<?php

namespace App\Filament\Resources\NewTransactionResource\Pages;

use App\Models\Item;
use App\Models\Customer;
use App\Models\TypeGalon;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\NewTransactionResource;
use Filament\Notifications\Notification;

class CreateNewTransaction extends CreateRecord
{
    protected static string $resource = NewTransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $existingCustomer = Customer::where('phone', $data['customer_phone'])->first();
        if ($existingCustomer) {
            Notification::make()
                ->title('No HP sudah terdaftar!')
                ->body('Nomor HP tersebut sudah digunakan oleh pelanggan lain.')
                ->danger()
                ->send();

            $this->halt();
        }
        $customer = Customer::firstOrCreate(
            ['name' => $data['customer_name']],
            [
                'phone' => $data['customer_phone'],
                'address' => $data['address'],
            ]
        );

        $data['customer_id'] = $customer->id;

        unset($data['customer_name'], $data['customer_phone'], $data['address']);

        return $data;
    }


    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        $item = Item::find($data['item_id']);
        if (!$item) {
            Notification::make()
                ->title('Barang tidak ditemukan!')
                ->danger()
                ->send();

            $this->halt();
        }

        if ($item->type === 'Air Mineral') {
            $galon = TypeGalon::find($data['id_type_galon']);

            if (!$galon) {
                Notification::make()
                    ->title('Jenis Galon tidak ditemukan!')
                    ->danger()
                    ->send();

                $this->halt();
            }

            $totalLiter = $galon->capacity * $data['jumlah'];

            if ($totalLiter > $item->stok) {
                Notification::make()
                    ->title('Stok tidak cukup!')
                    ->body("Stok tersisa: {$item->stok} liter, dibutuhkan: $totalLiter liter")
                    ->danger()
                    ->send();

                $this->halt();
            }
        } else {
            if ($data['jumlah'] > $item->stok) {
                Notification::make()
                    ->title('Stok tidak cukup!')
                    ->body("Stok tersisa: {$item->stok}, dibutuhkan: {$data['jumlah']}")
                    ->danger()
                    ->send();

                $this->halt();
            }
        }
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        $item = Item::find($record->item_id);
        if (!$item)
            return;

        if ($item->type === 'Air Mineral') {
            $typeGalon = TypeGalon::find($record->id_type_galon);
            if (!$typeGalon)
                return;

            $totalLiter = $typeGalon->capacity * $record->jumlah;
            $item->stok -= $totalLiter;
        } else {
            $item->stok -= $record->jumlah;
        }

        $item->save();
        $this->redirect(static::getResource()::getUrl('index'));
    }

    protected function getCreatedNotificationRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }

}

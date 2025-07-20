<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use App\Models\TypeGalon;
use Filament\Tables\Table;
use App\Models\NewTransaction;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\NewTransactionResource\Pages;
use App\Filament\Resources\NewTransactionResource\RelationManagers;

class NewTransactionResource extends Resource
{
    protected static ?string $model = NewTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Transaksi';


    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $customer = Customer::firstOrCreate(
            ['name' => $data['customer_name']],
            ['phone' => $data['customer_phone']],
        );

        $data['customer_id'] = $customer->id;

        unset($data['customer_name'], $data['customer_phone']);

        return $data;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Transaksi Baru')->schema([
                    TextInput::make('customer_name')
                        ->label('Nama Pelanggan')
                        ->required(),

                    TextInput::make('customer_phone')
                        ->label('No HP')
                        ->required(),

                    Select::make('item_id')
                        ->label('Nama Barang')
                        ->options(function () {
                            return \App\Models\Item::pluck('name', 'id');
                        })
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $item = \App\Models\Item::find($state);
                            if ($item) {
                                $set('harga_satuan', $item->price);
                                $set('type', $item->type);
                                if ($item->type !== 'Air Mineral') {
                                    $set('id_type_galon', null);
                                }
                            }
                        }),

                    Select::make('type')
                        ->label('Jenis Barang')
                        ->options([
                            'Air Mineral' => 'Air Mineral',
                            'Gas' => 'Gas',
                        ])
                        ->required()
                        ->disabled(), // biar otomatis dari item_id

                    Select::make('id_type_galon')
                        ->label('Jenis Galon')
                        ->options(fn() => TypeGalon::pluck('name', 'id'))
                        ->required(fn(callable $get) => $get('type') === 'Air Mineral')
                        ->visible(fn(callable $get) => $get('type') === 'Air Mineral')
                        ->reactive(),

                    TextInput::make('jumlah')
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $set('harga_total', ($get('harga_satuan') ?? 0) * $state);
                        }),

                    TextInput::make('harga_satuan')
                        ->numeric()
                        ->required()
                        ->readOnly()
                        ->reactive(),

                    TextInput::make('harga_total')
                        ->numeric()
                        ->required()
                        ->readOnly(),

                    Select::make('pengantaran')
                        ->label('Tipe Pengantaran')
                        ->options([
                            'diantar' => 'Diantar',
                            'diambil' => 'Diambil',
                        ])
                        ->required()
                        ->reactive(),
                    Select::make('kurir_id')
                        ->label('Kurir')
                        ->options(fn() => \App\Models\Kurir::pluck('name', 'id'))
                        ->visible(fn(callable $get) => $get('pengantaran') === 'diantar')
                        ->requiredIf('pengantaran', 'diantar'),

                    Textarea::make('alamat_pengantaran')
                        ->label('Alamat Pengantaran')
                        ->visible(fn(callable $get) => $get('pengantaran') === 'diantar')
                        ->requiredIf('pengantaran', 'diantar'),
                ]),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Nama Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('item.name')
                    ->label('Nama Barang')
                    ->searchable(),

                Tables\Columns\TextColumn::make('item.type')
                    ->label('Jenis Barang')
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'Air Mineral' => 'Air Mineral',
                        'Gas' => 'Gas',
                        default => 'Tidak diketahui',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('itemType.name')
                    ->label('Jenis Galon')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record || $record->item->type !== 'Air Mineral') {
                            return '-';
                        }
                        return $state ?? '-';
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah'),

                Tables\Columns\TextColumn::make('harga_total')
                    ->label('Harga Total')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('pengantaran')
                    ->label('Pengantaran'),

                Tables\Columns\TextColumn::make('kurir.name')
                    ->label('Kurir')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record || $record->pengantaran !== 'diantar') {
                            return '-';
                        }
                        return $state ?? '-';
                    }),

                Tables\Columns\TextColumn::make('alamat_pengantaran')
                    ->label('Alamat')
                    ->toggleable()
                    ->visible(fn($record) => $record?->pengantaran === 'diantar'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime('d M Y, H:i')
                    ->toggleable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }



    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewTransactions::route('/'),
            'create' => Pages\CreateNewTransaction::route('/create'),
            'edit' => Pages\EditNewTransaction::route('/{record}/edit'),
            'laporan' => Pages\LaporanPenjualan::route('/laporan'),
        ];
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getLabel(): string
    {
        return 'Transaksi';
    }

    public static function getPluralLabel(): string
    {
        return 'Transaksi';
    }
}

<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Delivery;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\NewTransaction;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DeliveryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DeliveryResource\RelationManagers;

class DeliveryResource extends Resource
{
    protected static ?string $model = NewTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pengantaran';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('pengantaran', 'diantar');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->label('Nama Pelanggan'),
                Tables\Columns\TextColumn::make('item.name')->label('Nama Barang'),
                Tables\Columns\TextColumn::make('jumlah'),
                Tables\Columns\TextColumn::make('harga_total')->money('IDR'),
                Tables\Columns\TextColumn::make('kurir.name')->label('Kurir'),
                Tables\Columns\BadgeColumn::make('pengantaran')
                    ->colors([
                        'success' => 'diambil',
                    ]),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function getLabel(): string
    {
        return 'Pengantaran';
    }

    public static function getPluralLabel(): string
    {
        return 'Pengantaran';
    }
}

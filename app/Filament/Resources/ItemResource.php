<?php

namespace App\Filament\Resources;

use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\ItemResource\Pages;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Data Barang';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Nama Barang')
                ->required()
                ->maxLength(255),

            TextInput::make('price')
                ->label('Harga Satuan')
                ->numeric()
                ->default(0)
                ->required(),

            Select::make('type')
                ->label('Jenis Barang')
                ->options([
                    'Gas' => 'Gas',
                    'Air Mineral' => 'Air Mineral',
                ])
                ->required(),

            Select::make('satuan')
                ->label('Satuan')
                ->options([
                    'Liter' => 'Liter',
                    'Tabung' => 'Tabung',
                ])
                ->required(),

            TextInput::make('stok')
                ->label('Stok')
                ->numeric()
                ->default(0)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('type')->label('Jenis'),
                TextColumn::make('price')->label('Harga Satuan')->money('IDR', true)->default(0),
                TextColumn::make('satuan'),
                TextColumn::make('stok')->label('Stok'),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}

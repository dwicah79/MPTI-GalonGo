<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\OtherTransaction;
use Filament\Resources\Resource;
use App\Models\OtherTransactions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OtherTransactionsResource\Pages;
use App\Filament\Resources\OtherTransactionsResource\RelationManagers;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class OtherTransactionsResource extends Resource
{
    protected static ?string $model = OtherTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kurir_id')
                    ->label('Kurir')
                    ->options(function () {
                        return \App\Models\Kurir::pluck('name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->placeholder('Pilih kurir untuk transaksi ini')
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label('Nama Transaksi')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->maxLength(500)
                    ->nullable(),
                TextInput::make('price')
                    ->label('Harga')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->maxValue(1000000000)
                    ->default(0)
                    ->placeholder('Masukkan harga transaksi')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kurir.name')
                    ->label('Kurir')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Transaksi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->description)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOtherTransactions::route('/'),
            'create' => Pages\CreateOtherTransactions::route('/create'),
            'edit' => Pages\EditOtherTransactions::route('/{record}/edit'),
        ];
    }

    public static function getNavigationSort(): ?int
    {
        return 6;
    }

    public static function getLabel(): string
    {
        return 'Kebutuhan Lain';
    }

    public static function getPluralLabel(): string
    {
        return 'Kebutuhan Lain';
    }
}

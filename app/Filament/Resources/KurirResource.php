<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KurirResource\Pages;
use App\Filament\Resources\KurirResource\RelationManagers;
use App\Models\Kurir;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KurirResource extends Resource
{
    protected static ?string $model = Kurir::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Kurir';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kurir')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->required()
                    ->maxLength(15),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kurir')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->dateTime('d M Y')
                    ->sortable(),
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
            'index' => Pages\ListKurirs::route('/'),
            'create' => Pages\CreateKurir::route('/create'),
            'edit' => Pages\EditKurir::route('/{record}/edit'),
        ];
    }
}

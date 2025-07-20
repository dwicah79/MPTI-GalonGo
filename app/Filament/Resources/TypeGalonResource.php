<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TypeGalonResource\Pages;
use App\Filament\Resources\TypeGalonResource\RelationManagers;
use App\Models\TypeGalon;
use BcMath\Number;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TypeGalonResource extends Resource
{
    protected static ?string $model = TypeGalon::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationLabel = 'Jenis Galon';
    protected static ?string $navigationGroup = 'Master Data';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('capacity')
                    ->label('Capacity (in liters)')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->maxValue(1000)
                    ->step(0.01)
                    ->default(0.00)
                    ->placeholder('Enter capacity in liters'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('capacity')
                    ->label('Capacity (liters)')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 2)),
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
            'index' => Pages\ListTypeGalons::route('/'),
            'create' => Pages\CreateTypeGalon::route('/create'),
            'edit' => Pages\EditTypeGalon::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return 'Data Kurir';
    }

    public static function getPluralLabel(): string
    {
        return 'Data Kurir';
    }
}

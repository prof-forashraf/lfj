<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyMetalPriceResource\Pages;
use App\Models\DailyMetalPrice;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DailyMetalPriceResource extends Resource
{
    protected static ?string $model = DailyMetalPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationGroup = '💰 Pricing & Market Data';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Daily Metal Prices';

    public static function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('price_date')
                ->label('Price Date')
                ->required(),
            Select::make('base_currency')
                ->label('Base Currency')
                ->options([
                    'USD' => 'USD',
                    'EUR' => 'EUR',
                    'GBP' => 'GBP',
                ])
                ->required(),
            TextInput::make('metal_symbol')
                ->label('Metal Symbol')
                ->required()
                ->maxLength(10),
            TextInput::make('price_per_unit')
                ->label('Price Per Unit')
                ->numeric()
                ->required(),
            TextInput::make('unit')
                ->label('Unit')
                ->required()
                ->maxLength(50),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('price_date')->label('Date')->date()->sortable(),
            TextColumn::make('base_currency')->sortable(),
            TextColumn::make('metal_symbol')->sortable(),
            TextColumn::make('price_per_unit')->numeric()->sortable(),
            TextColumn::make('unit')->sortable(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDailyMetalPrices::route('/'),
            'create' => Pages\CreateDailyMetalPrice::route('/create'),
            'edit' => Pages\EditDailyMetalPrice::route('/{record}/edit'),
        ];
    }
}

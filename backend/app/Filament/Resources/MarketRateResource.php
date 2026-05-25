<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketRateResource\Pages;
use App\Filament\Resources\MarketRateResource\RelationManagers;
use App\Models\MarketRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MarketRateResource extends Resource
{
    protected static ?string $model = MarketRate::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = '💰 Pricing & Market Data';

    protected static ?string $label = 'Market Rates';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('metal_type')
                    ->options([
                        'gold' => 'Gold',
                        'silver' => 'Silver',
                        'platinum' => 'Platinum',
                        'palladium' => 'Palladium',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->prefix('$'),
                Forms\Components\TextInput::make('currency')
                    ->default('USD')
                    ->maxLength(3)
                    ->required(),
                Forms\Components\DatePicker::make('rate_date')
                    ->default(now())
                    ->required(),
                Forms\Components\Hidden::make('updated_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('metal_type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('rate_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updater.name')
                    ->label('Updated By'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('metal_type')
                    ->options([
                        'gold' => 'Gold',
                        'silver' => 'Silver',
                        'platinum' => 'Platinum',
                        'palladium' => 'Palladium',
                    ]),
                Tables\Filters\Filter::make('today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('rate_date', today())),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            MarketRateResource\Widgets\MarketRateChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketRates::route('/'),
            'create' => Pages\CreateMarketRate::route('/create'),
            'edit' => Pages\EditMarketRate::route('/{record}/edit'),
        ];
    }
}

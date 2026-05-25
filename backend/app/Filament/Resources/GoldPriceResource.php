<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoldPriceResource\Pages;
use App\Models\GoldPrice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GoldPriceResource extends Resource
{
    protected static ?string $model = GoldPrice::class;

    // A more fitting icon for prices 💰
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = '💰 Pricing & Market Data';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('⚠️ API-MANAGED PRICES')
                ->description('These prices are automatically synchronized from the Metal API. Manual edits are disabled to preserve sync integrity and maintain accurate market history.')
                ->schema([
                    Forms\Components\Placeholder::make('locked')
                        ->content('This record is read-only and managed by the automated metal sync system. To correct prices, use daily_metal_prices and add notes for traceability.'),
                ]),

            // Grouping fields into sections makes the form much easier to read.
            Forms\Components\Section::make('Record Details')
                ->description('Core information about this price record.')
                ->disabled()
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\DatePicker::make('date_recorded')
                            ->label('Date of Price')
                            ->default(now())
                            ->required(),

                        // Using a Select input ensures data consistency for currency codes.
                        Forms\Components\Select::make('currency_code')
                            ->label('Currency')
                            ->options([
                                'USD' => 'USD - United States Dollar',
                                'EUR' => 'EUR - Euro',
                                'GBP' => 'GBP - British Pound',
                                'INR' => 'INR - Indian Rupee',
                                'CAD' => 'CAD - Canadian Dollar',
                                'AUD' => 'AUD - Australian Dollar',
                            ])
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('source')
                            ->maxLength(50)
                            ->default('Manual'),

                        Forms\Components\DateTimePicker::make('timestamp_recorded')
                            ->label('Timestamp of Price')
                            ->default(now()),
                    ]),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->helperText('Inactive prices will not be shown to users.')
                        ->default(true),
                ]),

            Forms\Components\Section::make('Gold Prices')
                ->description('API-synced prices per unit. Do not edit here.')
                ->disabled()
                ->schema([
                    // A 3-column grid for better layout of price fields.
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('price_per_ounce')
                            ->required()
                            ->numeric()
                            ->prefixIcon('heroicon-o-currency-dollar'), // Example with icon

                        Forms\Components\TextInput::make('price_per_gram_24k')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('price_per_gram_22k')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('price_per_gram_18k')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('price_per_gram_14k')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('price_per_gram_10k')
                            ->required()
                            ->numeric(),
                    ]),
                ]),

            Forms\Components\Section::make('Market Data (Optional)')
                ->description('Optional daily market performance data.')
                ->collapsible()
                ->disabled()
                ->schema([
                    Forms\Components\Grid::make(4)->schema([
                        Forms\Components\TextInput::make('market_open')->numeric(),
                        Forms\Components\TextInput::make('market_high')->numeric(),
                        Forms\Components\TextInput::make('market_low')->numeric(),
                        Forms\Components\TextInput::make('market_close')->numeric(),
                    ]),
                    Forms\Components\TextInput::make('volume')->numeric(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Show the newest records first by default.
            ->defaultSort('date_recorded', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('date_recorded')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency_code')
                    ->searchable()
                    ->badge(), // Using a badge makes the currency code stand out.

                // The `money` column automatically formats the number as a currency.
                Tables\Columns\TextColumn::make('price_per_ounce')
                    ->money(fn($record) => $record->currency_code) // Dynamically sets currency
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_per_gram_24k')
                    ->money(fn($record) => $record->currency_code)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hide less critical columns

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                // Display other price columns but keep them hidden by default.
                Tables\Columns\TextColumn::make('price_per_gram_22k')
                    ->money(fn($record) => $record->currency_code)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('price_per_gram_18k')
                    ->money(fn($record) => $record->currency_code)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->since() // Shows "2 hours ago" for better readability
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Adds a dropdown filter for the currency code.
                Tables\Filters\SelectFilter::make('currency_code')
                    ->label('Currency')
                    ->options([
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'GBP' => 'GBP',
                        'INR' => 'INR',
                    ]),
                // Adds a date range filter.
                Tables\Filters\Filter::make('date_recorded')
                    ->form([
                        Forms\Components\DatePicker::make('recorded_from'),
                        Forms\Components\DatePicker::make('recorded_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['recorded_from'], fn($query, $date) => $query->whereDate('date_recorded', '>=', $date))
                            ->when($data['recorded_until'], fn($query, $date) => $query->whereDate('date_recorded', '<=', $date));
                    }),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(), // Removed this action to fix the error.
                // Tables\Actions\EditAction::make(), // LOCKED: gold_prices are API-managed, never edited via UI
                // Tables\Actions\DeleteAction::make(), // LOCKED: gold_prices are API-managed, never deleted via UI
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
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
            'index' => Pages\ListGoldPrices::route('/'),
            // LOCKED: No create or edit pages. Gold prices are API-synced only.
            // 'create' => Pages\CreateGoldPrice::route('/create'),
            // 'edit' => Pages\EditGoldPrice::route('/{record}/edit'),
        ];
    }
}

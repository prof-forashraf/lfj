<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GemstonePricingResource\Pages;
use App\Models\GemstonePricing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GemstonePricingResource extends Resource
{
    protected static ?string $model = GemstonePricing::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = '💰 Pricing & Market Data';
    protected static ?int $navigationSort = 2; // Control the order in the sidebar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true) // `live` updates the slug field automatically
                            ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(GemstonePricing::class, 'slug', ignoreRecord: true)
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('price_per_carat')
                            ->label('Price per Carat')
                            ->required()
                            ->numeric()
                            ->prefix('$'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_carat')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGemstonePricings::route('/'),
            'create' => Pages\CreateGemstonePricing::route('/create'),
            'edit' => Pages\EditGemstonePricing::route('/{record}/edit'),
        ];
    }
}
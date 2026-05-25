<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiPricingToolResource\Pages;
use App\Models\AffiliateProduct;
use App\Services\DynamicPricingService;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Collection;

/**
 * AI Pricing Tool Resource (Read-Only Analytics)
 *
 * Provides AI-powered pricing insights for affiliate products.
 * This is a READ-ONLY view layered on top of AffiliateProduct data.
 * All product management should be done via AffiliateProductResource.
 *
 * Features:
 * - AI recommended pricing based on DynamicPricingService
 * - Confidence scores for pricing recommendations
 * - Color-coded indicators (success/warning/danger)
 *
 * Note: This resource is kept as an analytics dashboard. For product management,
 * use AffiliateProductResource instead.
 */
class AiPricingToolResource extends Resource
{
    protected static ?string $model = AffiliateProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'AI Pricing Analyzer';

    protected static ?string $navigationGroup = '🤖 AI Tools';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_name_snapshot')
                    ->label('Product Name')
                    ->required()
                    ->maxLength(255)
                    ->disabled(), // Read-only

                Forms\Components\TextInput::make('price')
                    ->label('Current Price')
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->disabled(), // Read-only

                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->disabled(), // Read-only

                Forms\Components\Toggle::make('is_featured')
                    ->label('Featured Product')
                    ->disabled(), // Read-only

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->maxLength(1000)
                    ->disabled(), // Read-only
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_name_snapshot')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Current Price')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\TextColumn::make('ai_recommended_price')
                    ->label('AI Recommended')
                    ->money('USD')
                    ->state(function (AffiliateProduct $record) {
                        $pricingService = app(DynamicPricingService::class);
                        $recommendation = $pricingService->calculateOptimalPrice($record);
                        return $recommendation['optimal_price'];
                    })
                    ->color(function (AffiliateProduct $record) {
                        $pricingService = app(DynamicPricingService::class);
                        $recommendation = $pricingService->calculateOptimalPrice($record);
                        $diff = $recommendation['optimal_price'] - $record->price;
                        return $diff > 5 ? 'success' : ($diff < -5 ? 'danger' : 'warning');
                    }),

                Tables\Columns\TextColumn::make('ai_confidence')
                    ->label('Confidence')
                    ->state(function (AffiliateProduct $record) {
                        $pricingService = app(DynamicPricingService::class);
                        $recommendation = $pricingService->calculateOptimalPrice($record);
                        return number_format($recommendation['confidence_score'] * 100, 1) . '%';
                    }),
            ])
            ->filters([])
            ->actions([
                // No edit/delete actions for this read-only analytics view
            ])
            ->bulkActions([
                // No bulk actions for this read-only analytics view
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAiPricingTools::route('/'),
        ];
    }

    // Disable all write operations - this is a read-only analytics view
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canForceDelete($record): bool
    {
        return false;
    }

    public static function canForceDeleteAny(): bool
    {
        return false;
    }

    public static function canRestore($record): bool
    {
        return false;
    }

    public static function canRestoreAny(): bool
    {
        return false;
    }
}
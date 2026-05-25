<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiamondPricingResource\Pages;
use App\Models\DiamondPricing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DiamondPricingResource extends Resource
{
    protected static ?string $model = DiamondPricing::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';
    protected static ?string $navigationGroup = '💰 Pricing & Market Data';
    protected static ?int $navigationSort = 3; // Set its order in the sidebar

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Carat & Price')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('carat_range_min')
                        ->required()->numeric()->step(0.01),
                    Forms\Components\TextInput::make('carat_range_max')
                        ->required()->numeric()->step(0.01),
                    Forms\Components\TextInput::make('base_price_per_carat')
                        ->required()->numeric()->prefix('$'),
                ]),

            Forms\Components\Section::make('The 4 Cs (Cut, Color, Clarity)')
                ->columns(3)
                ->schema([
                    Forms\Components\Select::make('cut_grade')
                        ->required()
                        ->options([
                            'Excellent' => 'Excellent',
                            'Very Good' => 'Very Good',
                            'Good' => 'Good',
                            'Fair' => 'Fair',
                            'Poor' => 'Poor',
                        ]),
                    Forms\Components\Select::make('color_grade')
                        ->required()
                        ->options([
                            'D' => 'D',
                            'E' => 'E',
                            'F' => 'F',
                            'G' => 'G',
                            'H' => 'H',
                            'I' => 'I',
                            'J' => 'J',
                            'K' => 'K',
                            'L' => 'L',
                            'M' => 'M',
                            'N' => 'N',
                        ]),
                    Forms\Components\Select::make('clarity_grade')
                        ->required()
                        ->options([
                            'FL' => 'FL (Flawless)',
                            'IF' => 'IF (Internally Flawless)',
                            'VVS1' => 'VVS1',
                            'VVS2' => 'VVS2',
                            'VS1' => 'VS1',
                            'VS2' => 'VS2',
                            'SI1' => 'SI1',
                            'SI2' => 'SI2',
                            'I1' => 'I1',
                            'I2' => 'I2',
                            'I3' => 'I3',
                        ]),
                ]),

            Forms\Components\Section::make('Additional Details (Optional)')
                ->collapsible()
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('shape')->default('Round'),
                    Forms\Components\Select::make('fluorescence')
                        ->options([
                            'None' => 'None',
                            'Faint' => 'Faint',
                            'Medium' => 'Medium',
                            'Strong' => 'Strong',
                        ])->default('None'),
                    Forms\Components\TextInput::make('certification')->default('GIA'),
                    Forms\Components\TextInput::make('market_region')->default('Global'),
                    Forms\Components\DatePicker::make('date_updated')
                        ->required()->default(now()),
                    Forms\Components\Toggle::make('is_active')->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('carat_range_min')
                    ->label('Carat Min')->sortable(),
                Tables\Columns\TextColumn::make('carat_range_max')
                    ->label('Carat Max')->sortable(),
                Tables\Columns\TextColumn::make('cut_grade')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('color_grade')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('clarity_grade')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('base_price_per_carat')
                    ->money('USD')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cut_grade')->options([
                    'Excellent' => 'Excellent',
                    'Very Good' => 'Very Good',
                    'Good' => 'Good',
                ]),
                Tables\Filters\SelectFilter::make('color_grade')->options([
                    'D' => 'D',
                    'E' => 'E',
                    'F' => 'F',
                    'G' => 'G',
                ]),
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
            'index' => Pages\ListDiamondPricings::route('/'),
            'create' => Pages\CreateDiamondPricing::route('/create'),
            'edit' => Pages\EditDiamondPricing::route('/{record}/edit'),
        ];
    }
}
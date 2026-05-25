<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JewelryMaterialResource\Pages;
use App\Models\JewelryMaterial;
use App\Models\MaterialType; // Import the Enum
use App\Models\UnitType;     // Import the Enum
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JewelryMaterialResource extends Resource
{
    protected static ?string $model = JewelryMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = '💰 Pricing & Market Data';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Core Details')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('material_type')
                        ->label('Material Type')
                        ->options(MaterialType::class)
                        ->required()
                        ->live(),

                    Forms\Components\TextInput::make('material_name')
                        ->label('Material Name')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\TextInput::make('material_category')
                        ->label('Category (e.g., Precious, Semi-Precious)')
                        ->maxLength(50),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Is Active')
                        ->default(true),
                ]),

            Forms\Components\Section::make('Pricing & Units')
                ->columns(3)
                ->description('Note: Prices here are cost basis only. Final customer prices include fabrication, labor, findings, gems, and margin.')
                ->schema([
                    Forms\Components\TextInput::make('price_per_unit')
                        ->label('Cost Basis Price')
                        ->helperText('Cost per unit (e.g., per gram). This is the input cost, not the final selling price.')
                        ->required()
                        ->numeric()
                        ->prefix('$'),

                    Forms\Components\TextInput::make('markup_percentage')
                        ->label('Markup Percentage')
                        ->helperText('Internal markup applied to cost basis for this material.')
                        ->numeric()
                        ->suffix('%')
                        ->default(0),

                    Forms\Components\Select::make('unit_type')
                        ->label('Unit')
                        ->options(UnitType::class)
                        ->required(),

                    Forms\Components\Select::make('currency_code')
                        ->label('Currency')
                        ->options(['USD' => 'USD'])
                        ->default('USD')
                        ->required(),
                ]),

            Forms\Components\Section::make('Quality & Supplier (Optional)')
                ->collapsible()
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('purity_grade')
                        ->label('Purity / Grade (e.g., 925, VS1)')
                        ->visible(fn(Forms\Get $get): bool => in_array($get('material_type'), [MaterialType::Metal, MaterialType::Gemstone])),

                    Forms\Components\TextInput::make('quality_grade')
                        ->label('Quality Grade'),

                    Forms\Components\TextInput::make('supplier')
                        ->label('Supplier'),

                    Forms\Components\DatePicker::make('date_updated')
                        ->label('Price Last Updated')
                        ->default(now())
                        ->required(),
                ]),
        ])->mutateFormDataUsing(function (array $data): array {
            return array_merge($data, [
                'is_auto_synced' => false,
                'manual_override' => true,
                'sync_source' => null,
                'last_synced_at' => null,
            ]);
        });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('sync_status')
                    ->label('Sync Status')
                    ->getStateUsing(function ($record) {
                        if ($record->is_auto_synced && $record->manual_override) {
                            return 'overridden';
                        }
                        return $record->is_auto_synced ? 'auto_synced' : 'manual';
                    })
                    ->colors([
                        'primary' => 'auto_synced',
                        'warning' => 'overridden',
                        'success' => 'manual',
                    ])
                    ->icons([
                        'heroicon-o-arrow-path' => 'auto_synced',
                        'heroicon-o-exclamation-circle' => 'overridden',
                        'heroicon-o-pencil' => 'manual',
                    ]),

                Tables\Columns\TextColumn::make('material_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('material_type')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price_per_unit')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_type')
                    ->label('Unit')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('markup_percentage')
                    ->label('Markup %')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('date_updated')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_synced_at')
                    ->label('Last Synced')
                    ->dateTime()
                    ->sortable()
                    ->visible(fn() => auth()->user()->hasRole(['Super-Admin', 'admin'])),
            ])
            ->filters([
                SelectFilter::make('material_type')
                    ->options(MaterialType::class),
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('is_auto_synced')
                    ->label('Auto Synced'),
                Tables\Filters\TernaryFilter::make('manual_override')
                    ->label('Overridden'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => ! $record->is_auto_synced),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_auto_synced', false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJewelryMaterials::route('/'),
            'create' => Pages\CreateJewelryMaterial::route('/create'),
            'edit' => Pages\EditJewelryMaterial::route('/{record}/edit'),
        ];
    }
}

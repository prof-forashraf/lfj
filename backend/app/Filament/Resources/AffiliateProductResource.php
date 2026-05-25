<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateProductResource\Pages;
use App\Filament\Support\SeoFormFields;
use App\Models\AffiliateProduct;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class AffiliateProductResource extends Resource
{
    protected static ?string $model = AffiliateProduct::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = '🛍️ Product & Affiliate Content';
    protected static ?string $recordTitleAttribute = 'product_name_snapshot';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                Section::make('Product Details')->schema([
                    TextInput::make('amazon_asin')->label('Amazon ASIN')->required()->maxLength(100),
                    TextInput::make('product_name_snapshot')->label('Product Name')->required()->maxLength(255),
                    TextInput::make('amazon_url')->label('Amazon URL')->url()->maxLength(2048),
                    TextInput::make('main_image_url_snapshot')->label('Primary Image URL')->url()->maxLength(2048),
                    TextInput::make('try_on_image_url')->label('Try-On Image URL')->url()->maxLength(2048),
                    TextInput::make('price')->label('Price')->numeric()->minValue(0)->step(0.01),
                    Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload(),
                    Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'draft' => 'Draft',
                            'inactive' => 'Inactive',
                        ])
                        ->required()
                        ->default('active'),
                    Toggle::make('is_featured')->label('Featured Product')->inline(false),
                ])->columnSpan(2),

                Section::make('Advanced Options')->schema([
                    TextInput::make('item_type')->label('Item Type')->maxLength(100),
                    TextInput::make('try_on_type')->label('Try-On Type')->maxLength(100),
                    TextInput::make('try_on_anchor')->label('Try-On Anchor')->maxLength(100),
                    TextInput::make('try_on_scale_default')->label('Try-On Scale')->numeric()->minValue(0)->maxValue(10)->step(0.1),
                    TextInput::make('try_on_offset_y')->label('Try-On Y Offset')->numeric()->minValue(-1000)->maxValue(1000),
                    Textarea::make('your_notes')->label('Notes')->rows(4),
                ])->columnSpan(1),
            ])->columns(3),
            Section::make('SEO & Structured Data')->schema(SeoFormFields::fields(defaultSchemaType: 'Product'))->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('local_image_path')
                ->label('Image')
                ->disk('public')
                ->square(),
            TextColumn::make('product_name_snapshot')->label('Product')->searchable()->sortable()->wrap(),
            TextColumn::make('amazon_asin')->label('ASIN')->sortable(),
            TextColumn::make('category.name')->label('Category')->sortable(),
            TextColumn::make('price')->label('Price')->money('usd')->sortable(),
            IconColumn::make('is_featured')->label('Featured')->boolean()->sortable(),
            TextColumn::make('status')->badge()->sortable(),
            TextColumn::make('updated_at')->label('Last Updated')->dateTime('M d, Y')->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'active' => 'Active',
                    'draft' => 'Draft',
                    'inactive' => 'Inactive',
                ]),
        ])->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAffiliateProducts::route('/'),
            'create' => Pages\CreateAffiliateProduct::route('/create'),
            'view' => Pages\ViewAffiliateProduct::route('/{record}'),
            'edit' => Pages\EditAffiliateProduct::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentPlacementResource\Pages;
use App\Models\ContentPlacement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContentPlacementResource extends Resource
{
    protected static ?string $model = ContentPlacement::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static ?string $navigationGroup = '🏪 Products & Affiliate';
    protected static ?string $navigationLabel = 'Content Placements';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Placement')->schema([
                Forms\Components\TextInput::make('page_key')
                    ->helperText('Examples: home, blog_post, shop_category, quiz_results.')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('section_key')
                    ->helperText('Examples: after_hero, mid_article, after_results.')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('block_type')
                    ->required()
                    ->options([
                        'product_rail' => 'Product Rail',
                        'blog_rail' => 'Blog Rail',
                        'video_spotlight' => 'Video Spotlight',
                        'interactive_cta' => 'Interactive CTA',
                        'shop_the_look' => 'Shop the Look',
                    ])
                    ->default('product_rail'),
                Forms\Components\TextInput::make('title')->maxLength(255),
                Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Controls')->schema([
                Forms\Components\Toggle::make('is_enabled')->default(true),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\DateTimePicker::make('starts_at'),
                Forms\Components\DateTimePicker::make('ends_at'),
                Forms\Components\KeyValue::make('settings')
                    ->helperText('Optional targeting data such as category, product_limit, video_search, CTA label, or campaign code.')
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page_key')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('section_key')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('block_type')->badge(),
                Tables\Columns\IconColumn::make('is_enabled')->boolean(),
                Tables\Columns\TextColumn::make('starts_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('ends_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentPlacements::route('/'),
            'create' => Pages\CreateContentPlacement::route('/create'),
            'edit' => Pages\EditContentPlacement::route('/{record}/edit'),
        ];
    }
}

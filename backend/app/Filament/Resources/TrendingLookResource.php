<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrendingLookResource\Pages;
use App\Filament\Resources\TrendingLookResource\RelationManagers;
use App\Models\TrendingLook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrendingLookResource extends Resource
{
    protected static ?string $model = TrendingLook::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = '🌟 Community & Social';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                Forms\Components\TextInput::make('image_url')
                    ->url()
                    ->required(),
                Forms\Components\TagsInput::make('jewelry_items')
                    ->placeholder('Add jewelry items')
                    ->helperText('Items featured in this look'),
                Forms\Components\Select::make('category')
                    ->options([
                        'Daily' => 'Daily',
                        'Weekly' => 'Weekly',
                        'Occasion' => 'Occasion',
                        'Celebrity' => 'Celebrity',
                        'Seasonal' => 'Seasonal',
                    ]),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'trending' => 'Trending',
                    ])
                    ->default('draft'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Creator'),
                Tables\Columns\TextColumn::make('category')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'blue',
                        'trending' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('likes')
                    ->sortable()
                    ->label('❤️ Likes'),
                Tables\Columns\TextColumn::make('views')
                    ->sortable()
                    ->label('👁️ Views'),
                Tables\Columns\TextColumn::make('shares')
                    ->sortable()
                    ->label('📤 Shares'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'trending' => 'Trending',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Daily' => 'Daily',
                        'Weekly' => 'Weekly',
                        'Occasion' => 'Occasion',
                        'Celebrity' => 'Celebrity',
                        'Seasonal' => 'Seasonal',
                    ]),
            ])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrendingLooks::route('/'),
            'create' => Pages\CreateTrendingLook::route('/create'),
            'edit' => Pages\EditTrendingLook::route('/{record}/edit'),
        ];
    }
}

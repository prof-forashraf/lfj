<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitorPreferenceResource\Pages;
use App\Models\VisitorPreference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VisitorPreferenceResource extends Resource
{
    protected static ?string $model = VisitorPreference::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'GDPR & Privacy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Visitor Information')
                    ->schema([
                        Forms\Components\TextInput::make('visitor_id')
                            ->required()
                            ->disabled()
                            ->maxLength(64),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Display Preferences')
                    ->schema([
                        Forms\Components\Select::make('theme')
                            ->options([
                                'light' => 'Light',
                                'dark' => 'Dark',
                                'system' => 'System',
                            ])
                            ->default('system'),
                        Forms\Components\Select::make('language')
                            ->options([
                                'en' => 'English',
                                'es' => 'Español',
                                'fr' => 'Français',
                                'de' => 'Deutsch',
                            ])
                            ->default('en'),
                        Forms\Components\Select::make('layout')
                            ->options([
                                'standard' => 'Standard',
                                'compact' => 'Compact',
                                'comfortable' => 'Comfortable',
                            ])
                            ->default('standard'),
                    ])->columns(3),

                Forms\Components\Section::make('Content Preferences')
                    ->schema([
                        Forms\Components\TagsInput::make('favorite_categories')
                            ->label('Favorite Categories')
                            ->columnSpanFull(),
                        Forms\Components\TagsInput::make('favorite_tags')
                            ->label('Favorite Tags')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Viewing History')
                    ->schema([
                        Forms\Components\Repeater::make('recently_viewed_products')
                            ->label('Recently Viewed Products')
                            ->schema([
                                Forms\Components\TextInput::make('product_id')
                                    ->label('Product ID'),
                                Forms\Components\TextInput::make('title')
                                    ->label('Title'),
                            ])
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('recently_viewed_posts')
                            ->label('Recently Viewed Posts')
                            ->schema([
                                Forms\Components\TextInput::make('post_id')
                                    ->label('Post ID'),
                                Forms\Components\TextInput::make('title')
                                    ->label('Title'),
                            ])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Saved Preferences')
                    ->schema([
                        Forms\Components\KeyValue::make('saved_preferences')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('visitor_id')
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('theme')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('language')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('layout')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('theme')
                    ->options([
                        'light' => 'Light',
                        'dark' => 'Dark',
                        'system' => 'System',
                    ]),
                Tables\Filters\SelectFilter::make('language')
                    ->options([
                        'en' => 'English',
                        'es' => 'Español',
                        'fr' => 'Français',
                        'de' => 'Deutsch',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisitorPreferences::route('/'),
            'view' => Pages\ViewVisitorPreference::route('/{record}'),
            'edit' => Pages\EditVisitorPreference::route('/{record}/edit'),
        ];
    }
}

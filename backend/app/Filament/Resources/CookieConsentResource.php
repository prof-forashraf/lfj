<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CookieConsentResource\Pages;
use App\Models\CookieConsent;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CookieConsentResource extends Resource
{
    protected static ?string $model = CookieConsent::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'GDPR & Privacy';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Visitor Information')
                ->schema([
                    TextInput::make('visitor_id')
                        ->required()
                        ->disabled()
                        ->maxLength(64),
                    TextInput::make('session_id')
                        ->maxLength(128),
                    TextInput::make('ip_address')
                        ->disabled(),
                    TextInput::make('user_agent')
                        ->disabled()
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Cookie Preferences')
                ->schema([
                    Toggle::make('essential')
                        ->label('Essential Cookies')
                        ->disabled()
                        ->helperText('Always enabled for site functionality'),
                    Toggle::make('analytics')
                        ->label('Analytics Cookies'),
                    Toggle::make('marketing')
                        ->label('Marketing Cookies'),
                    Toggle::make('preferences')
                        ->label('Preference Cookies'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Metadata')
                ->schema([
                    Forms\Components\KeyValue::make('meta')
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
                Tables\Columns\TextColumn::make('user_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('analytics')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('marketing')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('preferences')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('analytics')
                    ->label('Has Analytics Consent'),
                Tables\Filters\TernaryFilter::make('marketing')
                    ->label('Has Marketing Consent'),
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
            'index' => Pages\ListCookieConsents::route('/'),
            'view' => Pages\ViewCookieConsent::route('/{record}'),
            'edit' => Pages\EditCookieConsent::route('/{record}/edit'),
        ];
    }
}

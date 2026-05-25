<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CookieConsentLogResource\Pages;
use App\Models\CookieConsentLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class CookieConsentLogResource extends Resource
{
    protected static ?string $model = CookieConsentLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Cookie Management';
    protected static ?string $navigationLabel = 'Consent Logs';
    protected static ?string $pluralModelLabel = 'Cookie Consent Logs';
    protected static ?string $modelLabel = 'Consent Log';
    protected static ?string $slug = 'cookie-consent-logs';

    protected static bool $canCreate = false;
    protected static bool $canDelete = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('cookie_consent_id')
                ->label('Consent ID')
                ->disabled(),
            Forms\Components\TextInput::make('action')
                ->disabled(),
            Forms\Components\Textarea::make('previous_state')
                ->label('Previous State')
                ->disabled()
                ->rows(4),
            Forms\Components\Textarea::make('new_state')
                ->label('New State')
                ->disabled()
                ->rows(4),
            Forms\Components\TextInput::make('ip_address')
                ->label('IP Address')
                ->disabled(),
            Forms\Components\TextInput::make('user_agent')
                ->label('User Agent')
                ->disabled()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cookie_consent_id')
                    ->label('Consent ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCookieConsentLogs::route('/'),
            'view' => Pages\ViewCookieConsentLog::route('/{record}'),
        ];
    }
}

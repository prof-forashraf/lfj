<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JewelryMatchResource\Pages;
use App\Models\JewelryMatch;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JewelryMatchResource extends Resource
{
    protected static ?string $model = JewelryMatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = '💍 Jewelry & Design';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Jewelry Matches';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->label('User')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('gemstone_type')
                ->required()
                ->maxLength(255),
            TextInput::make('jewelry_type')
                ->required()
                ->maxLength(255),
            Select::make('difficulty_level')
                ->options([
                    'easy' => 'Easy',
                    'medium' => 'Medium',
                    'hard' => 'Hard',
                ])
                ->required(),
            TextInput::make('score')
                ->numeric()
                ->required(),
            TextInput::make('time_taken')
                ->label('Time Taken (seconds)')
                ->numeric()
                ->required(),
            Textarea::make('answer_details')
                ->label('Answer Details')
                ->rows(5)
                ->helperText('Store the player answer details as JSON or formatted text.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('user.name')->label('User')->sortable()->searchable(),
            TextColumn::make('gemstone_type')->sortable(),
            TextColumn::make('jewelry_type')->sortable(),
            TextColumn::make('difficulty_level')->sortable(),
            TextColumn::make('score')->sortable(),
            TextColumn::make('time_taken')->label('Time (sec)')->sortable(),
        ])
        ->filters([])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJewelryMatches::route('/'),
            'create' => Pages\CreateJewelryMatch::route('/create'),
            'edit' => Pages\EditJewelryMatch::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JewelryStyleQuizResource\Pages;
use App\Filament\Resources\JewelryStyleQuizResource\RelationManagers;
use App\Models\JewelryStyleQuiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JewelryStyleQuizResource extends Resource
{
    protected static ?string $model = JewelryStyleQuiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = '💍 Jewelry & Design';
    protected static ?int $navigationSort = 4;

    protected static ?string $label = 'Style Quizzes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable(),
                Forms\Components\Textarea::make('answers')
                    ->json(),
                Forms\Components\TextInput::make('personality_type'),
                Forms\Components\Textarea::make('recommendations')
                    ->json(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User'),
                Tables\Columns\TextColumn::make('personality_type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('personality_type')
                    ->options([
                        'Classic' => 'Classic',
                        'Bohemian' => 'Bohemian',
                        'Modern' => 'Modern',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            JewelryStyleQuizResource\Widgets\QuizAnalyticsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJewelryStyleQuizzes::route('/'),
            'create' => Pages\CreateJewelryStyleQuiz::route('/create'),
            'edit' => Pages\EditJewelryStyleQuiz::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JewelryHoroscopeResource\Pages;
use App\Filament\Resources\JewelryHoroscopeResource\RelationManagers;
use App\Models\JewelryHoroscope;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JewelryHoroscopeResource extends Resource
{
    protected static ?string $model = JewelryHoroscope::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = '💍 Jewelry & Design';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('zodiac_sign')
                    ->options([
                        'Aries' => 'Aries',
                        'Taurus' => 'Taurus',
                        'Gemini' => 'Gemini',
                        'Cancer' => 'Cancer',
                        'Leo' => 'Leo',
                        'Virgo' => 'Virgo',
                        'Libra' => 'Libra',
                        'Scorpio' => 'Scorpio',
                        'Sagittarius' => 'Sagittarius',
                        'Capricorn' => 'Capricorn',
                        'Aquarius' => 'Aquarius',
                        'Pisces' => 'Pisces',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->default(now())
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\TagsInput::make('recommended_jewelry')
                    ->placeholder('Add jewelry recommendations')
                    ->helperText('Press Enter to add each recommendation'),
                Forms\Components\TextInput::make('lucky_color'),
                Forms\Components\TextInput::make('lucky_gemstone'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('zodiac_sign')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
                Tables\Columns\TextColumn::make('lucky_gemstone'),
                Tables\Columns\TextColumn::make('lucky_color'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('zodiac_sign')
                    ->options([
                        'Aries' => 'Aries',
                        'Taurus' => 'Taurus',
                        'Gemini' => 'Gemini',
                        'Cancer' => 'Cancer',
                        'Leo' => 'Leo',
                        'Virgo' => 'Virgo',
                        'Libra' => 'Libra',
                        'Scorpio' => 'Scorpio',
                        'Sagittarius' => 'Sagittarius',
                        'Capricorn' => 'Capricorn',
                        'Aquarius' => 'Aquarius',
                        'Pisces' => 'Pisces',
                    ]),
                Tables\Filters\Filter::make('today')
                    ->query(fn (Builder $query): Builder => $query->where('date', now()->toDateString())),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJewelryHoroscopes::route('/'),
            'create' => Pages\CreateJewelryHoroscope::route('/create'),
            'edit' => Pages\EditJewelryHoroscope::route('/{record}/edit'),
        ];
    }
}

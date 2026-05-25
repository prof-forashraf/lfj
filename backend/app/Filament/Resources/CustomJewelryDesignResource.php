<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomJewelryDesignResource\Pages;
use App\Models\CustomJewelryDesign;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomJewelryDesignResource extends Resource
{
    protected static ?string $model = CustomJewelryDesign::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = '💍 Jewelry & Design';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Custom Jewelry Designs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->label('Customer')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('name')
                ->label('Design Name')
                ->required()
                ->maxLength(255),

            TextInput::make('jewelry_type')
                ->label('Jewelry Type')
                ->required()
                ->maxLength(100),

            TextInput::make('estimated_price')
                ->label('Estimated Price')
                ->numeric()
                ->required(),

            Textarea::make('materials')
                ->label('Materials')
                ->rows(4)
                ->helperText('Enter one material per line.')
                ->dehydrateStateUsing(fn ($state) => is_string($state)
                    ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $state))))
                    : $state)
                ->hydrateStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : $state),

            Textarea::make('customizations')
                ->label('Customizations')
                ->rows(4)
                ->helperText('Enter one customization per line.')
                ->dehydrateStateUsing(fn ($state) => is_string($state)
                    ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $state))))
                    : $state)
                ->hydrateStateUsing(fn ($state) => is_array($state) ? implode("\n", $state) : $state),

            Toggle::make('is_public')
                ->label('Publicly Visible')
                ->helperText('Allow this design to be visible for internal review or public catalog listing.'),

            FileUpload::make('preview_image')
                ->label('Preview Image')
                ->image()
                ->imageEditor()
                ->directory('custom-jewelry-designs')
                ->disk('public')
                ->helperText('Upload a preview image for the custom design.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('user.name')->label('Customer')->sortable()->searchable(),
            TextColumn::make('jewelry_type')->sortable(),
            TextColumn::make('estimated_price')->money('USD')->sortable(),
            TextColumn::make('is_public')->boolean()->label('Public'),
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
            'index' => Pages\ListCustomJewelryDesigns::route('/'),
            'create' => Pages\CreateCustomJewelryDesign::route('/create'),
            'edit' => Pages\EditCustomJewelryDesign::route('/{record}/edit'),
        ];
    }
}

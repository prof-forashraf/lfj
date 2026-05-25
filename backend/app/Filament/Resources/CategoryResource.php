<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Support\SeoFormFields;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

// Import Filament components
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle; // <-- ADDED
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn; // <-- ADDED (or BooleanColumn)

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = '🏷️ Organization';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Category Details')->schema([ // Renamed for clarity
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))), // Basic slug
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Category::class, 'slug', ignoreRecord: true),
                    ]),
                    FileUpload::make('image')
                        ->label('Category Image')
                        ->image()
                        ->imagePreviewHeight('250')
                        ->directory('categories')
                        ->required()
                        ->helperText('Upload the category image used in the frontend.'),
                    Textarea::make('description')
                        ->rows(3)
                        ->maxLength(65535)
                        ->label('Description (for category page)'),
                    Select::make('parent_id')
                        ->label('Parent Category')
                        ->relationship('parent', 'name')
                        ->searchable()
                        ->preload()
                        ->helperText('Select if this is a subcategory.'),
                    Toggle::make('is_featured') // <-- ADDED TOGGLE
                        ->label('Featured on Homepage')
                        ->helperText('Display this category in the "Featured Categories" section on the homepage.')
                        ->inline(false), // Display label above toggle
                ])->columnSpan(2),

                Section::make('SEO & Meta')
                    ->schema(SeoFormFields::fields(defaultSchemaType: 'CollectionPage'))
                    ->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->sortable(),
                TextColumn::make('parent.name')->label('Parent Category')->sortable()->placeholder('N/A'),
                IconColumn::make('is_featured') // <-- ADDED ICON COLUMN
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('posts_count')->counts('posts')->label('Posts')->sortable(),
                TextColumn::make('updated_at')->dateTime('d-M-Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured') // <-- ADDED FILTER
                    ->label('Is Featured'),
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
            // RelationManagers\ChildrenCategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'), // Corrected from CreateCategories
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}

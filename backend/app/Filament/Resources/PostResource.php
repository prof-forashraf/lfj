<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Support\SeoFormFields;
use App\Models\Post;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle; // <-- Make sure Toggle is imported
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = '📝 Content Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make()->schema([
                // Main Column Group
                Forms\Components\Group::make()->schema([
                    Tabs::make('Post Editor')->tabs([
                        Tabs\Tab::make('Main Content')->icon('heroicon-o-pencil-square')->schema([
                            TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')->required()->maxLength(255)->unique(Post::class, 'slug', ignoreRecord: true),
                            RichEditor::make('content')->required()->columnSpanFull(),
                            Textarea::make('excerpt')->rows(3)->maxLength(65535),
                        ]),
                        Tabs\Tab::make('SEO & Meta')->icon('heroicon-o-magnifying-glass-circle')->schema(SeoFormFields::fields(post: true, defaultSchemaType: 'Article')),
                    ])->columnSpanFull(),
                ])->columnSpan(['lg' => 2]),

                // Sidebar Column Group
                Forms\Components\Group::make()->schema([
                    Section::make('Featured Image')->schema([
                        FileUpload::make('featured_image')
                            ->label('Featured Image')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('posts')
                            ->helperText('Upload the featured image used in listings.'),
                        TextInput::make('featured_image_url_snapshot')->label('OR Paste Image URL')->url()->helperText('Used if no file is uploaded.'),
                    ]),
                    // --- THIS IS THE FIX ---
                    // Combine all details into a single, correct section.
                    Section::make('Details & Organization')->schema([
                        Select::make('status')->options(['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'])->required()->default('draft'),
                        Toggle::make('is_featured')->label('Featured Post')->default(false), // <-- Correctly placed here
                        DateTimePicker::make('published_at')->label('Publish Date & Time'),
                        Select::make('user_id')->label('Author')->relationship('author', 'name')->searchable()->preload()->required()->default(auth()->id()),
                        Select::make('categories')->multiple()->relationship('categories', 'name')->preload()->searchable(),
                        Select::make('tags')->multiple()->relationship('tags', 'name')->preload()->searchable()->createOptionForm([
                            TextInput::make('name')->required()->live(onBlur: true)->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')->required()->unique(Tag::class, 'slug', ignoreRecord: true),
                        ]),
                        Select::make('affiliateProducts')->label('Featured Affiliate Products')->multiple()->relationship('affiliateProducts', 'product_name_snapshot')->preload()->searchable(),
                    ]),
                ])->columnSpan(['lg' => 1]),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        // Your table definition is good, just adding the is_featured column
        return $table->columns([
            ImageColumn::make('featured_image')->disk('public')->square(),
            TextColumn::make('title')->searchable()->sortable(),
            IconColumn::make('is_featured')->label('Featured')->boolean(), // <-- Added for visibility
            TextColumn::make('status')->badge()->color(fn(string $state): string => match ($state) { 'draft' => 'gray', 'published' => 'success', 'archived' => 'warning', }),
            TextColumn::make('published_at')->dateTime('d-M-Y'),
        ])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

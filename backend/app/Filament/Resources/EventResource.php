<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Support\SeoFormFields;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = '🗓️ Events & Experiences';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                Section::make('Event Details')->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Event::class, 'slug', ignoreRecord: true),
                    Textarea::make('description')
                        ->label('Description')
                        ->rows(6)
                        ->columnSpanFull(),
                    DateTimePicker::make('start_datetime')->label('Start Date & Time')->required(),
                    DateTimePicker::make('end_datetime')->label('End Date & Time'),
                    TextInput::make('location_name')->label('Location Name')->maxLength(255),
                    TextInput::make('location_address')->label('Location Address')->maxLength(500),
                    TextInput::make('event_url')->label('Event Link')->url()->maxLength(2048),
                    TextInput::make('featured_image_url_snapshot')->label('Featured Image URL')->url()->maxLength(2048),
                    FileUpload::make('image')
                        ->label('Upload Event Image')
                        ->image()
                        ->imagePreviewHeight('250')
                        ->directory('events')
                        ->helperText('Uploading a new image will replace the current featured image on save.')
                        ->imageEditor(),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'upcoming' => 'Upcoming',
                            'past' => 'Past',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('upcoming')
                        ->required(),
                ])->columnSpan(2),

                Section::make('SEO & Structured Data')->schema(SeoFormFields::fields(defaultSchemaType: 'Event'))->columnSpan(1),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->searchable()->sortable()->wrap(),
            TextColumn::make('status')->badge()->sortable(),
            TextColumn::make('start_datetime')->label('Start')->dateTime('M d, Y H:i')->sortable(),
            TextColumn::make('end_datetime')->label('End')->dateTime('M d, Y H:i')->sortable(),
            TextColumn::make('location_name')->label('Location')->sortable()->wrap(),
        ])->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}

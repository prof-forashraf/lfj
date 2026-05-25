<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components; // Import Infolist Components

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(), // Link to edit page
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Event Information')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('title'),
                        Components\TextEntry::make('slug'),
                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'upcoming' => 'primary',
                                'past' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        Components\TextEntry::make('start_datetime')->dateTime('F j, Y H:i A'),
                        Components\TextEntry::make('end_datetime')->dateTime('F j, Y H:i A'),
                        Components\TextEntry::make('location_name'),
                        Components\TextEntry::make('location_address'),
                        Components\TextEntry::make('event_url')
                            ->url(fn (?string $state): ?string => $state)
                            ->openUrlInNewTab()
                            ->label('Event Link'),
                        Components\ImageEntry::make('featured_image')
                            ->disk('public')
                            ->label('Featured Image')
                            ->height(150) // Or your preferred size
                            ->columnSpanFull(),
                    ]),
                Components\Section::make('Description')
                    ->schema([
                        Components\TextEntry::make('description')
                            ->html() // If using RichEditor for description
                            // ->markdown() // Or if using MarkdownEditor
                            ->columnSpanFull(),
                    ]),
                Components\Section::make('SEO Details')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('seo_title'),
                        Components\TextEntry::make('meta_description'),
                    ])
            ]);
    }
}
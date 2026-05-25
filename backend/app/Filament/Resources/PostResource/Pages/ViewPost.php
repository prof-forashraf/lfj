<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist; // Import Infolist
use Filament\Infolists\Components; // Import Infolist Components

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(), // Link to edit page
        ];
    }

    // Define the Infolist structure
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Post Details')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('title'),
                        Components\TextEntry::make('slug'),
                        Components\TextEntry::make('author.name')->label('Author'),
                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'gray',
                                'published' => 'success',
                                'archived' => 'warning',
                                default => 'primary',
                            }),
                        Components\TextEntry::make('published_at')->dateTime('d-M-Y H:i'),
                        Components\ImageEntry::make('featured_image')
                            ->disk('public')
                            ->columnSpanFull()
                            ->label('Featured Image')
                            ->height(150), // Adjust as needed
                    ]),
                Components\Section::make('Content')
                    ->schema([
                        Components\TextEntry::make('excerpt')
                            ->columnSpanFull()
                            ->markdown(), // Or ->html() if content is HTML
                        Components\TextEntry::make('content')
                            ->columnSpanFull()
                            ->html() // Assuming RichEditor content is HTML
                            ->label('Main Content'),
                    ]),
                Components\Section::make('SEO Information')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('seo_title'),
                        Components\TextEntry::make('meta_description'),
                        Components\TextEntry::make('meta_keywords'),
                        Components\TextEntry::make('canonical_url')->url(fn ($state) => $state)->openUrlInNewTab(),
                        Components\IconEntry::make('noindex')->boolean()->label('No Index'),
                        Components\IconEntry::make('nofollow')->boolean()->label('No Follow'),
                    ]),
            ]);
    }
}
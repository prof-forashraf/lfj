<?php

namespace App\Filament\Resources\AffiliateProductResource\Pages;

use App\Filament\Resources\AffiliateProductResource;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Resources\Pages\ViewRecord;

class ViewAffiliateProduct extends ViewRecord
{
    protected static string $resource = AffiliateProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Product Details')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('product_name_snapshot')->label('Product Name'),
                        Components\TextEntry::make('amazon_asin')->label('Amazon ASIN'),
                        Components\TextEntry::make('status')->label('Status'),
                        Components\TextEntry::make('price')->label('Price'),
                        Components\TextEntry::make('item_type')->label('Item Type'),
                        Components\TextEntry::make('category.name')->label('Category'),
                        Components\TextEntry::make('amazon_url')
                            ->label('Amazon Product URL')
                            ->url(fn (?string $state): ?string => $state)
                            ->openUrlInNewTab(),
                        Components\TextEntry::make('main_image_url_snapshot')
                            ->label('Primary Image URL')
                            ->url(fn (?string $state): ?string => $state)
                            ->openUrlInNewTab(),
                    ]),
                Components\Section::make('Images')
                    ->schema([
                        Components\ImageEntry::make('local_image_path')
                            ->label('Stored Image')
                            ->disk('public')
                            ->height(240),
                    ])->columnSpanFull(),
                Components\Section::make('SEO & Notes')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('seo_title')->label('SEO Title'),
                        Components\TextEntry::make('meta_description')->label('Meta Description'),
                        Components\TextEntry::make('your_notes')->label('Notes')->columnSpanFull(),
                    ]),
            ]);
    }
}

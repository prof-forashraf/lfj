<?php

namespace App\Filament\Resources\DailyMetalPriceResource\Pages;

use App\Filament\Resources\DailyMetalPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyMetalPrices extends ListRecords
{
    protected static string $resource = DailyMetalPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

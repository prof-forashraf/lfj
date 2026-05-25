<?php

namespace App\Filament\Resources\MarketRateResource\Pages;

use App\Filament\Resources\MarketRateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketRates extends ListRecords
{
    protected static string $resource = MarketRateResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            MarketRateResource\Widgets\MarketRateChart::class,
        ];
    }
}

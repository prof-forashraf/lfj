<?php

namespace App\Filament\Resources\DiamondPricingResource\Pages;

use App\Filament\Resources\DiamondPricingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiamondPricings extends ListRecords
{
    protected static string $resource = DiamondPricingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

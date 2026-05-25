<?php

namespace App\Filament\Resources\DiamondPricingResource\Pages;

use App\Filament\Resources\DiamondPricingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiamondPricing extends EditRecord
{
    protected static string $resource = DiamondPricingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

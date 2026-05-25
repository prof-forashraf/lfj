<?php

namespace App\Filament\Resources\GemstonePricingResource\Pages;

use App\Filament\Resources\GemstonePricingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGemstonePricing extends EditRecord
{
    protected static string $resource = GemstonePricingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

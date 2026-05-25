<?php

namespace App\Filament\Resources\AiPricingToolResource\Pages;

use App\Filament\Resources\AiPricingToolResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAiPricingTools extends ListRecords
{
    protected static string $resource = AiPricingToolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

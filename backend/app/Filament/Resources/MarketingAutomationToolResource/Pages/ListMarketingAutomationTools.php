<?php

namespace App\Filament\Resources\MarketingAutomationToolResource\Pages;

use App\Filament\Resources\MarketingAutomationToolResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketingAutomationTools extends ListRecords
{
    protected static string $resource = MarketingAutomationToolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

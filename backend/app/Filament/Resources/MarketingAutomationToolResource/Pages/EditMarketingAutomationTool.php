<?php

namespace App\Filament\Resources\MarketingAutomationToolResource\Pages;

use App\Filament\Resources\MarketingAutomationToolResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketingAutomationTool extends EditRecord
{
    protected static string $resource = MarketingAutomationToolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

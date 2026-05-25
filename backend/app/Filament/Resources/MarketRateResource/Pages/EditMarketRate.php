<?php

namespace App\Filament\Resources\MarketRateResource\Pages;

use App\Filament\Resources\MarketRateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketRate extends EditRecord
{
    protected static string $resource = MarketRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

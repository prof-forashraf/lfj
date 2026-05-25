<?php

namespace App\Filament\Resources\PredictiveAnalyticsToolResource\Pages;

use App\Filament\Resources\PredictiveAnalyticsToolResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPredictiveAnalyticsTools extends ListRecords
{
    protected static string $resource = PredictiveAnalyticsToolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

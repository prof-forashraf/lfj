<?php

namespace App\Filament\Resources\PredictiveAnalyticsToolResource\Pages;

use App\Filament\Resources\PredictiveAnalyticsToolResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPredictiveAnalyticsTool extends EditRecord
{
    protected static string $resource = PredictiveAnalyticsToolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

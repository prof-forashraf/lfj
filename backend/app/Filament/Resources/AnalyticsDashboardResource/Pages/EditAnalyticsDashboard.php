<?php

namespace App\Filament\Resources\AnalyticsDashboardResource\Pages;

use App\Filament\Resources\AnalyticsDashboardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalyticsDashboard extends EditRecord
{
    protected static string $resource = AnalyticsDashboardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

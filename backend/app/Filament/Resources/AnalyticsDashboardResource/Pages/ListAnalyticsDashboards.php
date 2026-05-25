<?php

namespace App\Filament\Resources\AnalyticsDashboardResource\Pages;

use App\Filament\Resources\AnalyticsDashboardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnalyticsDashboards extends ListRecords
{
    protected static string $resource = AnalyticsDashboardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

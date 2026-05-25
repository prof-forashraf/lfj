<?php

namespace App\Filament\Resources\TrendingLookResource\Pages;

use App\Filament\Resources\TrendingLookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrendingLooks extends ListRecords
{
    protected static string $resource = TrendingLookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

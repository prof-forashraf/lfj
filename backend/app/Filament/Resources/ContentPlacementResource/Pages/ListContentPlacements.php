<?php

namespace App\Filament\Resources\ContentPlacementResource\Pages;

use App\Filament\Resources\ContentPlacementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContentPlacements extends ListRecords
{
    protected static string $resource = ContentPlacementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

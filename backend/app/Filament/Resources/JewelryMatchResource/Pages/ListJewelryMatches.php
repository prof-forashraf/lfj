<?php

namespace App\Filament\Resources\JewelryMatchResource\Pages;

use App\Filament\Resources\JewelryMatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJewelryMatches extends ListRecords
{
    protected static string $resource = JewelryMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

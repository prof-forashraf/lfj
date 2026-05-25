<?php

namespace App\Filament\Resources\JewelryHoroscopeResource\Pages;

use App\Filament\Resources\JewelryHoroscopeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJewelryHoroscopes extends ListRecords
{
    protected static string $resource = JewelryHoroscopeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\JewelryHoroscopeResource\Pages;

use App\Filament\Resources\JewelryHoroscopeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJewelryHoroscope extends EditRecord
{
    protected static string $resource = JewelryHoroscopeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

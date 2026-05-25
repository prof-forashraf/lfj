<?php

namespace App\Filament\Resources\JewelryMaterialResource\Pages;

use App\Filament\Resources\JewelryMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJewelryMaterial extends EditRecord
{
    protected static string $resource = JewelryMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

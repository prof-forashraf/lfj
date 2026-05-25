<?php

namespace App\Filament\Resources\CustomJewelryDesignResource\Pages;

use App\Filament\Resources\CustomJewelryDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomJewelryDesigns extends ListRecords
{
    protected static string $resource = CustomJewelryDesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

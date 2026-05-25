<?php

namespace App\Filament\Resources\VisitorPreferenceResource\Pages;

use App\Filament\Resources\VisitorPreferenceResource;
use Filament\Resources\Pages\ListRecords;

class ListVisitorPreferences extends ListRecords
{
    protected static string $resource = VisitorPreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}

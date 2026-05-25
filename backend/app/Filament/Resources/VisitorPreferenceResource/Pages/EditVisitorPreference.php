<?php

namespace App\Filament\Resources\VisitorPreferenceResource\Pages;

use App\Filament\Resources\VisitorPreferenceResource;
use Filament\Resources\Pages\EditRecord;

class EditVisitorPreference extends EditRecord
{
    protected static string $resource = VisitorPreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}

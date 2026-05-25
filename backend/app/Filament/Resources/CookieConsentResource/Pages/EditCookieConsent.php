<?php

namespace App\Filament\Resources\CookieConsentResource\Pages;

use App\Filament\Resources\CookieConsentResource;
use Filament\Resources\Pages\EditRecord;

class EditCookieConsent extends EditRecord
{
    protected static string $resource = CookieConsentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}

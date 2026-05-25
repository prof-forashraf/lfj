<?php

namespace App\Filament\Resources\CookieConsentLogResource\Pages;

use App\Filament\Resources\CookieConsentLogResource;
use Filament\Resources\Pages\ListRecords;

class ListCookieConsentLogs extends ListRecords
{
    protected static string $resource = CookieConsentLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}

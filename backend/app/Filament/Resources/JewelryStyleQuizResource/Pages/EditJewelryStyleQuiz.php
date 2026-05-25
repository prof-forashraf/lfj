<?php

namespace App\Filament\Resources\JewelryStyleQuizResource\Pages;

use App\Filament\Resources\JewelryStyleQuizResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJewelryStyleQuiz extends EditRecord
{
    protected static string $resource = JewelryStyleQuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

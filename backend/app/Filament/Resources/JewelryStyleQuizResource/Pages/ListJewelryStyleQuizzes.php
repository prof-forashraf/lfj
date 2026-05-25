<?php

namespace App\Filament\Resources\JewelryStyleQuizResource\Pages;

use App\Filament\Resources\JewelryStyleQuizResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJewelryStyleQuizzes extends ListRecords
{
    protected static string $resource = JewelryStyleQuizResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            JewelryStyleQuizResource\Widgets\QuizAnalyticsWidget::class,
        ];
    }
}

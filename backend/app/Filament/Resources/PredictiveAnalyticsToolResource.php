<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PredictiveAnalyticsToolResource\Pages;
use App\Services\PredictiveAnalyticsService;
use Filament\Resources\Resource;
use Filament\Pages\Page;

class PredictiveAnalyticsToolResource extends Resource
{
    protected static ?string $model = null;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'Predictive Analytics';

    protected static ?string $navigationGroup = '🤖 AI Tools';

    protected static ?int $navigationSort = 3;

    public static function getPages(): array
    {
        return [
            'index' => Pages\PredictiveAnalyticsDashboard::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canView($record): bool
    {
        return false;
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnalyticsDashboardResource\Pages;
use Filament\Resources\Resource;
use Filament\Pages\Page;

/**
 * Analytics Dashboard Resource
 *
 * A custom dashboard page (not a standard CRUD resource) that displays
 * business analytics and key performance indicators.
 *
 * Configuration:
 * - No model association (protected static ?string $model = null)
 * - No create, edit, delete, or view capabilities
 * - Accessible only through the custom AnalyticsDashboard page
 * - Icon and labels configured for navigation
 */
class AnalyticsDashboardResource extends Resource
{
    protected static ?string $model = null;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Analytics Dashboard';
    protected static ?string $navigationGroup = '📊 Analytics & Engagement';
    protected static ?int $navigationSort = 1;

    public static function getPages(): array
    {
        return [
            'index' => Pages\AnalyticsDashboard::route('/'),
        ];
    }

    // Disable all CRUD operations - this is a view-only dashboard
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

    // Prevent standard list view access
    public static function canViewAny(): bool
    {
        return false;
    }
}

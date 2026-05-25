<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

/**
 * Filament Admin Dashboard Page
 *
 * Renders the main admin panel dashboard at /admin with:
 * - Key metric widgets showing system health and activity
 * - SEO health monitoring
 * - User and content statistics
 * - Quick access navigation to resources
 * 
 * This page is automatically loaded by Filament when admins access /admin
 * and serves as the primary landing point for authenticated admin users.
 */
class Dashboard extends BaseDashboard
{
    /**
     * Get the widgets to display on the dashboard.
     * 
     * Filament auto-discovers widgets from app/Filament/Widgets,
     * but this method can override or supplement those.
     *
     * @return array
     */
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AdminOverviewWidget::class,
            \App\Filament\Widgets\QuickReportsWidget::class,
            \App\Filament\Widgets\ActionItemsWidget::class,
            \Filament\Widgets\AccountWidget::class,
            \Filament\Widgets\FilamentInfoWidget::class,
            \App\Filament\Widgets\SeoHealthWidget::class,
        ];
    }

    /**
     * Get the columns that the widgets should be arranged in.
     * 
     * Default is 1 column for smaller screens, 2 for larger.
     * Can be customized with getColumns() method.
     *
     * @return int|string|array
     */
    public function getColumns(): int|string|array
    {
        return [
            'md' => 2,
            'lg' => 3,
        ];
    }
}

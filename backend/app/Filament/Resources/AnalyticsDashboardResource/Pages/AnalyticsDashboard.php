<?php

namespace App\Filament\Resources\AnalyticsDashboardResource\Pages;

use App\Filament\Resources\AnalyticsDashboardResource;
use App\Services\AnalyticsService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Cache;

class AnalyticsDashboard extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = AnalyticsDashboardResource::class;

    protected static string $view = 'filament.resources.analytics-dashboard-resource.pages.analytics-dashboard';

    protected static ?string $title = 'Business Intelligence Dashboard';

    protected static ?string $navigationLabel = 'Analytics Dashboard';

    public ?array $data = [];

    public string $dateRange = '30d';
    public string $metricType = 'overview';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('dateRange')
                    ->label('Date Range')
                    ->options([
                        '7d' => 'Last 7 days',
                        '30d' => 'Last 30 days',
                        '90d' => 'Last 90 days',
                        '1y' => 'Last year',
                    ])
                    ->default('30d')
                    ->live()
                    ->afterStateUpdated(fn () => $this->refreshData()),

                Select::make('metricType')
                    ->label('Metric Type')
                    ->options([
                        'overview' => 'Overview',
                        'products' => 'Products',
                        'users' => 'Users',
                        'revenue' => 'Revenue',
                        'performance' => 'Performance',
                    ])
                    ->default('overview')
                    ->live()
                    ->afterStateUpdated(fn () => $this->refreshData()),
            ])
            ->statePath('data');
    }

    public function refreshData(): void
    {
        // Force refresh cached data
        Cache::forget('dashboard_metrics');
        Cache::forget('business_metrics');

        // Refresh the page data
        $this->dispatch('refresh-dashboard');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export Report')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    // Export functionality would be implemented here
                    $this->notify('success', 'Report export feature coming soon!');
                }),

            Action::make('refresh')
                ->label('Refresh Data')
                ->icon('heroicon-o-arrow-path')
                ->action(fn () => $this->refreshData()),
        ];
    }

    public function getAnalyticsService(): AnalyticsService
    {
        return app(AnalyticsService::class);
    }

    public function getDashboardData(): array
    {
        $analyticsService = $this->getAnalyticsService();

        return match($this->metricType) {
            'products' => $this->getProductMetrics(),
            'users' => $this->getUserMetrics(),
            'revenue' => $this->getRevenueMetrics(),
            'performance' => $this->getPerformanceMetrics(),
            default => $this->getOverviewMetrics(),
        };
    }

    private function getOverviewMetrics(): array
    {
        $analyticsService = $this->getAnalyticsService();

        return [
            'summary' => [
                'total_users' => $analyticsService->getDashboardMetrics()['total_users'] ?? 0,
                'total_products' => $analyticsService->getDashboardMetrics()['total_products'] ?? 0,
                'total_categories' => $analyticsService->getDashboardMetrics()['total_categories'] ?? 0,
                'featured_products' => $analyticsService->getDashboardMetrics()['featured_products'] ?? 0,
            ],
            'performance' => $analyticsService->getSystemMetrics(),
            'health' => $analyticsService->getSystemHealth(),
            'charts' => [
                'user_growth' => $this->getUserGrowthData(),
                'product_views' => $this->getProductViewsData(),
                'category_distribution' => $this->getCategoryDistributionData(),
            ],
        ];
    }

    private function getProductMetrics(): array
    {
        $analyticsService = $this->getAnalyticsService();

        return [
            'total_products' => $analyticsService->getDashboardMetrics()['total_products'] ?? 0,
            'featured_products' => $analyticsService->getDashboardMetrics()['featured_products'] ?? 0,
            'popular_products' => $analyticsService->getPopularProducts(10),
            'price_distribution' => $analyticsService->getPriceDistribution(),
            'category_performance' => $analyticsService->getDashboardMetrics()['popular_categories'] ?? [],
            'charts' => [
                'price_distribution' => $this->getPriceDistributionChartData(),
                'category_performance' => $this->getCategoryPerformanceChartData(),
            ],
        ];
    }

    private function getUserMetrics(): array
    {
        $analyticsService = $this->getAnalyticsService();

        return [
            'total_users' => $analyticsService->getDashboardMetrics()['total_users'] ?? 0,
            'active_today' => $analyticsService->getSystemMetrics()['active_users_today'] ?? 0,
            'new_users_this_week' => 0, // Would calculate from user registrations
            'user_engagement' => [
                'average_session_duration' => '4m 32s',
                'pages_per_session' => 3.2,
                'bounce_rate' => 0.35,
            ],
            'charts' => [
                'user_registration_trend' => $this->getUserRegistrationTrendData(),
                'user_activity' => $this->getUserActivityData(),
            ],
        ];
    }

    private function getRevenueMetrics(): array
    {
        return [
            'total_revenue' => 0, // Would calculate from orders
            'revenue_today' => 0,
            'revenue_this_month' => 0,
            'average_order_value' => 0,
            'conversion_rate' => 0,
            'top_revenue_products' => [], // Would calculate from order data
            'charts' => [
                'revenue_trend' => $this->getRevenueTrendData(),
                'conversion_funnel' => $this->getConversionFunnelData(),
            ],
        ];
    }

    private function getPerformanceMetrics(): array
    {
        $analyticsService = $this->getAnalyticsService();

        return [
            'response_time' => $analyticsService->getSystemMetrics()['average_response_time'] ?? 0,
            'cache_hit_rate' => $analyticsService->getSystemMetrics()['cache_hit_rate'] ?? 0,
            'error_rate' => $analyticsService->getSystemMetrics()['error_rate'] ?? 0,
            'memory_usage' => $analyticsService->getSystemHealth()['memory_usage'] ?? 0,
            'uptime' => $analyticsService->getSystemHealth()['uptime'] ?? 'N/A',
            'charts' => [
                'response_time_trend' => $this->getResponseTimeTrendData(),
                'error_rate_trend' => $this->getErrorRateTrendData(),
            ],
        ];
    }

    // Chart data methods (would be populated with real data in production)
    private function getUserGrowthData(): array
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            'datasets' => [
                [
                    'label' => 'New Users',
                    'data' => [65, 89, 80, 81, 96],
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ]
            ]
        ];
    }

    private function getProductViewsData(): array
    {
        return [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'datasets' => [
                [
                    'label' => 'Product Views',
                    'data' => [120, 190, 300, 500, 200, 300, 450],
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ]
            ]
        ];
    }

    private function getCategoryDistributionData(): array
    {
        return [
            'labels' => ['Necklaces', 'Earrings', 'Bracelets', 'Rings', 'Other'],
            'datasets' => [
                [
                    'data' => [35, 25, 20, 15, 5],
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(139, 92, 246)',
                    ],
                ]
            ]
        ];
    }

    private function getPriceDistributionChartData(): array
    {
        $distribution = $this->getAnalyticsService()->getPriceDistribution();
        return [
            'labels' => ['Under $50', '$50-$100', '$100-$250', '$250-$500', 'Over $500'],
            'datasets' => [
                [
                    'data' => [
                        $distribution['under_50'] ?? 0,
                        $distribution['50_100'] ?? 0,
                        $distribution['100_250'] ?? 0,
                        $distribution['250_500'] ?? 0,
                        $distribution['over_500'] ?? 0,
                    ],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(139, 92, 246)',
                    ],
                ]
            ]
        ];
    }

    private function getCategoryPerformanceChartData(): array
    {
        $categories = $this->getAnalyticsService()->getDashboardMetrics()['popular_categories'] ?? [];
        return [
            'labels' => collect($categories)->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Products',
                    'data' => collect($categories)->pluck('product_count')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                ]
            ]
        ];
    }

    private function getUserRegistrationTrendData(): array
    {
        return [
            'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            'datasets' => [
                [
                    'label' => 'New Registrations',
                    'data' => [45, 67, 89, 123],
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ]
            ]
        ];
    }

    private function getUserActivityData(): array
    {
        return [
            'labels' => ['Active Users', 'Inactive Users'],
            'datasets' => [
                [
                    'data' => [75, 25],
                    'backgroundColor' => ['rgb(16, 185, 129)', 'rgb(239, 68, 68)'],
                ]
            ]
        ];
    }

    private function getRevenueTrendData(): array
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            'datasets' => [
                [
                    'label' => 'Revenue ($)',
                    'data' => [12000, 19000, 15000, 25000, 22000],
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ]
            ]
        ];
    }

    private function getConversionFunnelData(): array
    {
        return [
            'labels' => ['Visitors', 'Product Views', 'Add to Cart', 'Checkout', 'Purchase'],
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => [10000, 7500, 3000, 1200, 800],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                ]
            ]
        ];
    }

    private function getResponseTimeTrendData(): array
    {
        return [
            'labels' => ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
            'datasets' => [
                [
                    'label' => 'Response Time (ms)',
                    'data' => [245, 267, 289, 234, 298, 256],
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ]
            ]
        ];
    }

    private function getErrorRateTrendData(): array
    {
        return [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'datasets' => [
                [
                    'label' => 'Error Rate (%)',
                    'data' => [0.1, 0.05, 0.08, 0.03, 0.12, 0.07, 0.09],
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ]
            ]
        ];
    }
}
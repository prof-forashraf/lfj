<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SyncGoldApiPricesJob;
use App\Repositories\DailyMetalPriceRepository;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MonitoringController extends Controller
{
    protected AnalyticsService $analyticsService;
    protected DailyMetalPriceRepository $dailyMetalPriceRepository;

    public function __construct(AnalyticsService $analyticsService, DailyMetalPriceRepository $dailyMetalPriceRepository)
    {
        $this->analyticsService = $analyticsService;
        $this->dailyMetalPriceRepository = $dailyMetalPriceRepository;
    }

    /**
     * Get comprehensive system health and metrics
     */
    public function health(Request $request)
    {
        try {
            $systemHealth = $this->analyticsService->getSystemHealth();
            $businessMetrics = $this->analyticsService->getDashboardMetrics();
            $systemMetrics = $this->analyticsService->getSystemMetrics();

            $data = [
                'status' => 'healthy',
                'timestamp' => now()->toISOString(),
                'version' => config('app.version', '1.0.0'),
                'environment' => config('app.env'),
                'system_health' => $systemHealth,
                'business_metrics' => $businessMetrics,
                'system_metrics' => $systemMetrics,
                'response_time_ms' => (microtime(true) - LARAVEL_START) * 1000,
                'metal_sync' => $this->analyticsService->getMetalSyncHealth(),
            ];

            // Log health check for monitoring
            Log::channel('api')->info('Health Check', [
                'status' => 'healthy',
                'response_time' => $data['response_time_ms'],
                'memory_usage' => $systemHealth['memory_usage'],
            ]);

            return response()->json($data);

        } catch (\Exception $e) {
            Log::channel('api')->error('Health Check Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'unhealthy',
                'timestamp' => now()->toISOString(),
                'error' => $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Get performance metrics
     */
    public function performance(Request $request)
    {
        $period = $request->get('period', '24h'); // 1h, 24h, 7d, 30d

        // This would typically aggregate data from logs or a time-series database
        // For now, return current metrics
        $metrics = [
            'period' => $period,
            'average_response_time' => $this->analyticsService->getSystemMetrics()['average_response_time'],
            'cache_hit_rate' => $this->analyticsService->getSystemMetrics()['cache_hit_rate'],
            'error_rate' => $this->analyticsService->getSystemMetrics()['error_rate'],
            'throughput' => $this->getThroughputMetrics($period),
            'memory_usage_trend' => $this->getMemoryUsageTrend($period),
        ];

        return response()->json($metrics);
    }

    /**
     * Get business analytics
     */
    public function analytics(Request $request)
    {
        $type = $request->get('type', 'overview'); // overview, products, users, revenue

        switch ($type) {
            case 'products':
                return response()->json($this->getProductAnalytics());
            case 'users':
                return response()->json($this->getUserAnalytics());
            case 'revenue':
                return response()->json($this->getRevenueAnalytics());
            default:
                return response()->json($this->getOverviewAnalytics());
        }
    }

    /**
     * Get product-specific analytics
     */
    public function productAnalytics(Request $request, $productId)
    {
        $days = $request->get('days', 30);

        return response()->json(
            $this->analyticsService->getProductViews($productId, $days)
        );
    }

    public function syncMetalPrices(Request $request)
    {
        $symbols = $request->input('symbols', ['XAU', 'XAG']);
        $currency = strtoupper($request->input('currency', 'USD'));

        SyncGoldApiPricesJob::dispatch($symbols, $currency)
            ->onQueue(config('services.metal_sync.queue_name', 'metal-sync'));

        return response()->json([
            'status' => 'queued',
            'symbols' => array_map('strtoupper', $symbols),
            'currency' => $currency,
            'message' => 'Metal price synchronization job has been queued successfully.',
            'queue' => [
                'name' => config('services.metal_sync.queue_name', 'metal-sync'),
                'connection' => config('queue.default'),
                'driver' => config('queue.connections.' . config('queue.default') . '.driver', 'unknown'),
                'last_queue_wait_ms' => Cache::get('metal_sync:last_queue_wait_ms'),
                'last_lock_wait_ms' => Cache::get('metal_sync:last_lock_wait_ms'),
                'last_lock_status' => Cache::get('metal_sync:last_lock_status'),
            ],
        ]);
    }

    public function latestMetalPrices(Request $request)
    {
        $symbols = $request->input('symbols', ['XAU', 'XAG']);
        $currency = strtoupper($request->input('currency', 'USD'));
        $symbols = array_map('strtoupper', $symbols);

        $prices = $this->dailyMetalPriceRepository->latestForSymbols($symbols, $currency)
            ->map(function ($row) {
                return [
                    'metal_symbol' => $row->metal_symbol,
                    'base_currency' => $row->base_currency,
                    'price_date' => $row->price_date->toDateString(),
                    'price_per_unit' => (float) $row->price_per_unit,
                    'unit' => $row->unit,
                ];
            });

        return response()->json([
            'status' => 'success',
            'currency' => $currency,
            'prices' => $prices,
        ]);
    }

    private function getThroughputMetrics(string $period): array
    {
        // Placeholder - would aggregate from logs
        return [
            'requests_per_second' => 15.5,
            'requests_per_minute' => 930,
            'peak_hour' => '14:00-15:00',
        ];
    }

    private function getMemoryUsageTrend(string $period): array
    {
        // Placeholder - would aggregate from monitoring data
        return [
            'current' => memory_get_peak_usage(true) / 1024 / 1024,
            'average' => 85.5,
            'peak' => 120.0,
            'trend' => 'stable',
        ];
    }

    private function getProductAnalytics(): array
    {
        return [
            'total_products' => $this->analyticsService->getDashboardMetrics()['total_products'],
            'featured_products' => $this->analyticsService->getDashboardMetrics()['featured_products'],
            'popular_products' => $this->analyticsService->getPopularProducts(5),
            'placement_performance' => $this->analyticsService->getPlacementPerformance(30),
            'price_distribution' => $this->analyticsService->getPriceDistribution(),
            'category_distribution' => $this->analyticsService->getDashboardMetrics()['popular_categories'],
        ];
    }

    private function getUserAnalytics(): array
    {
        return [
            'total_users' => $this->analyticsService->getDashboardMetrics()['total_users'],
            'active_today' => $this->analyticsService->getSystemMetrics()['active_users_today'],
            'new_users_this_week' => 0, // Would calculate from user registrations
            'user_engagement' => [
                'average_session_duration' => '4m 32s',
                'pages_per_session' => 3.2,
                'bounce_rate' => 0.35,
            ],
        ];
    }

    private function getRevenueAnalytics(): array
    {
        return [
            'total_revenue' => 0, // Would calculate from orders
            'revenue_today' => 0,
            'revenue_this_month' => 0,
            'average_order_value' => 0,
            'conversion_rate' => 0,
        ];
    }

    private function getOverviewAnalytics(): array
    {
        return [
            'summary' => [
                'total_users' => $this->analyticsService->getDashboardMetrics()['total_users'],
                'total_products' => $this->analyticsService->getDashboardMetrics()['total_products'],
                'total_orders' => 0,
                'total_revenue' => 0,
            ],
            'performance' => $this->analyticsService->getSystemMetrics(),
            'health' => $this->analyticsService->getSystemHealth(),
        ];
    }
}

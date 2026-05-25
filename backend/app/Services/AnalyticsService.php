<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class AnalyticsService
{
    /**
     * Get dashboard metrics for admin panel
     */
    public function getDashboardMetrics(): array
    {
        try {
            return Cache::remember('dashboard_metrics', now()->addMinutes(30), function () {
                return [
                    'total_products' => DB::table('affiliate_products')->where('status', 'active')->count(),
                    'total_categories' => DB::table('categories')->count(),
                    'total_posts' => DB::table('posts')->where('status', 'published')->count(),
                    'total_users' => DB::table('users')->count(),
                    'featured_products' => DB::table('affiliate_products')->where('is_featured', true)->count(),
                    'recent_audit_logs' => DB::table('audit_logs')->latest()->take(5)->get(),
                    'popular_categories' => DB::table('affiliate_products')
                        ->join('categories', 'affiliate_products.category_id', '=', 'categories.id')
                        ->select('categories.name', DB::raw('count(*) as product_count'))
                        ->where('affiliate_products.status', 'active')
                        ->groupBy('categories.id', 'categories.name')
                        ->orderBy('product_count', 'desc')
                        ->take(5)
                        ->get(),
                ];
            });
        } catch (\Throwable $e) {
            return [
                'total_products' => 0,
                'total_categories' => 0,
                'total_posts' => 0,
                'total_users' => 0,
                'featured_products' => 0,
                'recent_audit_logs' => collect(),
                'popular_categories' => collect(),
            ];
        }
    }

    /**
     * Get popular products based on recent activity
     */
    public function getPopularProducts(int $limit = 10): array
    {
        return Cache::remember("popular_products_{$limit}", now()->addHours(1), function () use ($limit) {
            return DB::table('affiliate_products')
                ->where('status', 'active')
                ->select('id', 'product_name_snapshot', 'price', 'image_url', 'amazon_asin', 'is_featured')
                ->orderBy('updated_at', 'desc') // Using updated_at as proxy for popularity
                ->take($limit)
                ->get()
                ->toArray();
        });
    }

    /**
     * Track product view analytics
     */
    public function trackProductView(int $productId, ?int $userId = null): void
    {
        DB::table('product_analytics')->insert([
            'product_id' => $productId,
            'user_id' => $userId,
            'action' => 'view',
            'metadata' => json_encode([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'referer' => request()->header('referer'),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\Log::channel('api')->info('Product View', [
            'product_id' => $productId,
            'user_id' => $userId,
            'timestamp' => now()->toISOString(),
            'ip' => request()->ip(),
        ]);
    }

    /**
     * Get price range distribution
     */
    public function getPriceDistribution(): array
    {
        return Cache::remember('price_distribution', now()->addHours(6), function () {
            return [
                'under_50' => DB::table('affiliate_products')->where('price', '<', 50)->count(),
                '50_100' => DB::table('affiliate_products')->whereBetween('price', [50, 100])->count(),
                '100_250' => DB::table('affiliate_products')->whereBetween('price', [100, 250])->count(),
                '250_500' => DB::table('affiliate_products')->whereBetween('price', [250, 500])->count(),
                'over_500' => DB::table('affiliate_products')->where('price', '>', 500)->count(),
            ];
        });
    }

    /**
     * Get system performance metrics
     */
    public function getSystemMetrics(): array
    {
        return [
            'cache_hit_rate' => $this->calculateCacheHitRate(),
            'average_response_time' => $this->getAverageResponseTime(),
            'error_rate' => $this->getErrorRate(),
            'active_users_today' => $this->getActiveUsersToday(),
        ];
    }

    private function calculateCacheHitRate(): float
    {
        // This would require more sophisticated cache monitoring
        // For now, return a placeholder
        return 0.85; // 85% cache hit rate
    }

    private function getAverageResponseTime(): float
    {
        // This would require logging response times
        // For now, return a placeholder
        return 250.0; // 250ms average
    }

    private function getErrorRate(): float
    {
        // This would require error logging analysis
        // For now, return a placeholder
        return 0.02; // 2% error rate
    }

    private function getActiveUsersToday(): int
    {
        return DB::table('users')
            ->where('last_login_at', '>=', now()->startOfDay())
            ->count();
    }

    /**
     * Get system health metrics for monitoring
     */
    public function getSystemHealth(): array
    {
        return [
            'database' => $this->checkDatabaseHealth(),
            'cache' => $this->checkCacheHealth(),
            'storage' => $this->checkStorageHealth(),
            'memory_usage' => memory_get_peak_usage(true) / 1024 / 1024, // MB
            'uptime' => $this->getUptime(),
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Get metal sync health indicators for monitoring
     */
    public function getMetalSyncHealth(): array
    {
        $lastSuccess = Cache::get('metal_sync:last_success');
        $lastFailed = Cache::get('metal_sync:last_failed');
        $duration = Cache::get('metal_sync:last_duration_ms');
        $rows = Cache::get('metal_sync:last_rows_updated');
        $cacheRefreshCount = Cache::get('metal_sync:last_cache_refresh_count');

        // Latest cached prices for XAU/XAG in USD (if present)
        $latest = [];
        foreach (['XAU', 'XAG'] as $symbol) {
            $key = "metal_prices:{$symbol}:USD:latest";
            $latest[$symbol] = Cache::get($key);
        }

        $freshThreshold = config('services.metal_sync.stale_threshold_minutes', 30);
        $lastSuccessAt = $lastSuccess ? Carbon::parse($lastSuccess) : null;
        $isCacheFresh = $lastSuccessAt ? now()->diffInMinutes($lastSuccessAt) <= $freshThreshold : false;

        return [
            'last_success' => $lastSuccess,
            'last_failed' => $lastFailed,
            'last_duration_ms' => $duration,
            'last_execution_duration_ms' => Cache::get('metal_sync:last_execution_duration_ms'),
            'last_execution_memory_mb' => Cache::get('metal_sync:last_execution_memory_mb'),
            'last_execution_started_at' => Cache::get('metal_sync:last_execution_started_at'),
            'last_rows_updated' => $rows,
            'last_cache_refresh_count' => $cacheRefreshCount,
            'last_prune_run' => Cache::get('metal_sync:last_prune_run'),
            'last_prune_duration_ms' => Cache::get('metal_sync:last_prune_duration_ms'),
            'last_pruned_rows' => Cache::get('metal_sync:last_pruned_rows'),
            'last_prune_status' => Cache::get('metal_sync:last_prune_status', 'unknown'),
            'last_queue_wait_ms' => Cache::get('metal_sync:last_queue_wait_ms'),
            'last_lock_wait_ms' => Cache::get('metal_sync:last_lock_wait_ms'),
            'last_lock_status' => Cache::get('metal_sync:last_lock_status'),
            'consecutive_failures' => Cache::get('metal_sync:consecutive_failures', 0),
            'circuit_breaker' => Cache::get('metal_sync:circuit_breaker', [
                'status' => 'closed',
                'failures' => 0,
                'opened_at' => null,
                'cooldown_until' => null,
                'last_checked_at' => null,
            ]),
            'cache_status' => $isCacheFresh ? 'fresh' : 'stale',
            'queue' => [
                'connection' => config('queue.default'),
                'name' => config('services.metal_sync.queue_name', 'metal-sync'),
                'driver' => config('queue.connections.' . config('queue.default') . '.driver', 'unknown'),
            ],
            'latest_cached_prices' => $latest,
        ];
    }

    private function checkDatabaseHealth(): bool
    {
        try {
            DB::select('SELECT 1');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkCacheHealth(): bool
    {
        try {
            Cache::put('health_check', 'ok', 10);
            return Cache::get('health_check') === 'ok';
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function checkStorageHealth(): bool
    {
        try {
            $testFile = storage_path('health_check.tmp');
            file_put_contents($testFile, 'ok');
            $result = file_get_contents($testFile) === 'ok';
            unlink($testFile);
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getUptime(): string
    {
        if (function_exists('posix_getpid')) {
            $uptime = time() - filemtime('/proc/' . posix_getpid() . '/stat');
            return gmdate('H:i:s', $uptime);
        }

        return 'N/A';
    }

    /**
     * Enhanced product view tracking with detailed analytics
     */
    public function trackProductViewDetailed(int $productId, ?int $userId = null): void
    {
        $key = "product_views:{$productId}:" . now()->format('Y-m-d');
        Cache::increment($key);

        // Log detailed analytics
        \Illuminate\Support\Facades\Log::channel('api')->info('Product View Detailed', [
            'product_id' => $productId,
            'user_id' => $userId,
            'timestamp' => now()->toISOString(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
            'session_id' => session()->getId(),
        ]);
    }

    public function trackProductClick(int $productId, ?int $userId = null, array $metadata = []): void
    {
        DB::table('product_analytics')->insert([
            'product_id' => $productId,
            'user_id' => $userId,
            'action' => 'click',
            'metadata' => json_encode(array_merge([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'referer' => request()->header('referer'),
            ], $metadata)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Cache::forget('dashboard_metrics');
    }

    public function getPlacementPerformance(int $days = 30): array
    {
        return DB::table('product_analytics')
            ->join('affiliate_products', 'product_analytics.product_id', '=', 'affiliate_products.id')
            ->select(
                'affiliate_products.id',
                'affiliate_products.product_name_snapshot',
                DB::raw("SUM(CASE WHEN product_analytics.action = 'view' THEN 1 ELSE 0 END) as views"),
                DB::raw("SUM(CASE WHEN product_analytics.action = 'click' THEN 1 ELSE 0 END) as clicks")
            )
            ->where('product_analytics.created_at', '>=', now()->subDays($days))
            ->groupBy('affiliate_products.id', 'affiliate_products.product_name_snapshot')
            ->orderByDesc('clicks')
            ->limit(20)
            ->get()
            ->map(fn ($row) => [
                'id' => $row->id,
                'product_name' => $row->product_name_snapshot,
                'views' => (int) $row->views,
                'clicks' => (int) $row->clicks,
                'ctr' => (int) $row->views > 0 ? round(((int) $row->clicks / (int) $row->views) * 100, 2) : 0,
            ])
            ->toArray();
    }

    /**
     * Get product view statistics
     */
    public function getProductViews(int $productId, int $days = 30): array
    {
        $views = 0;
        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            $key = "product_views:{$productId}:{$date}";
            $views += Cache::get($key, 0);
        }

        return [
            'total_views' => $views,
            'average_daily' => round($views / $days, 2),
            'period_days' => $days,
            'product_id' => $productId,
        ];
    }
}

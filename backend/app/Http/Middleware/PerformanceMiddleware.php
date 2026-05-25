<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $duration = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = ($endMemory - $startMemory) / 1024 / 1024; // Convert to MB

        // Log performance metrics for API routes
        if ($request->is('api/*')) {
            Log::channel('api')->info('API Performance', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'duration_ms' => round($duration, 2),
                'memory_mb' => round($memoryUsage, 2),
                'status_code' => $response->getStatusCode(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);

            // Alert on slow requests (>500ms)
            if ($duration > 500) {
                Log::channel('api')->warning('Slow API Request', [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'duration_ms' => round($duration, 2),
                ]);
            }
        }

        // Add performance headers to response
        $response->headers->set('X-Response-Time', round($duration, 2) . 'ms');
        $response->headers->set('X-Memory-Usage', round($memoryUsage, 2) . 'MB');

        return $response;
    }
}
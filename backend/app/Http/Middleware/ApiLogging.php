<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiLogging
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $startTime;

        // Only log API requests
        if ($request->is('api/*')) {
            $logData = [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'duration_ms' => round($duration * 1000, 2),
                'status' => $response->getStatusCode(),
                'user_id' => auth()->id(),
                'memory_peak' => memory_get_peak_usage(true),
            ];

            // Log to different channels based on response time and status
            if ($response->getStatusCode() >= 400) {
                Log::channel('api')->warning('API Error', $logData);
            } elseif ($duration > 1.0) { // Slow requests (> 1 second)
                Log::channel('api')->warning('Slow API Request', $logData);
            } else {
                Log::channel('api')->info('API Request', $logData);
            }
        }

        return $response;
    }
}
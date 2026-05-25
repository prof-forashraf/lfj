<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CookieConsent;

class GateAnalyticsConsent
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $visitorId = $this->getVisitorId($request);

        // Check if visitor has analytics consent
        $consent = CookieConsent::where('visitor_id', $visitorId)
            ->latest()
            ->first();

        $hasAnalyticsConsent = $consent && $consent->analytics;

        // Attach consent status to request
        $request->attributes->set('has_analytics_consent', $hasAnalyticsConsent);
        $request->attributes->set('visitor_id', $visitorId);

        // If analytics tracking is required and not consented, block it
        if ($this->shouldBlockAnalytics($request) && !$hasAnalyticsConsent) {
            return response()->json(['error' => 'Analytics consent required'], 403);
        }

        return $next($request);
    }

    /**
     * Extract or generate visitor ID from request.
     */
    private function getVisitorId(Request $request): string
    {
        // Try to get from cookie first
        $visitorId = $request->cookie('visitor_id');
        if ($visitorId) {
            return $visitorId;
        }

        // Try to get from header
        $visitorId = $request->header('X-Visitor-ID');
        if ($visitorId) {
            return $visitorId;
        }

        // Try to get from authenticated user
        if (auth('sanctum')->check()) {
            $userId = auth('sanctum')->user()->id;
            return "user_{$userId}";
        }

        // Generate a new one
        return (string) Str::uuid();
    }

    /**
     * Determine if this request requires analytics consent.
     */
    private function shouldBlockAnalytics(Request $request): bool
    {
        $blockedPaths = [
            '/api/monitoring/analytics',
            '/api/monitoring/performance',
            '/api/monitoring/products/*/analytics',
        ];

        $path = $request->getPathInfo();

        foreach ($blockedPaths as $blockedPath) {
            if (preg_match('~' . preg_quote($blockedPath, '~') . '~', $path)) {
                return true;
            }
        }

        return false;
    }
}

<?php

namespace App\Jobs\Middleware;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Support\Facades\Cache;

class TrackMetalSyncMetrics
{
    public function handle($job, $next)
    {
        $payload = null;

        if (method_exists($job, 'payload')) {
            $payload = $job->payload();
        }

        $queueWaitMs = null;

        if (is_array($payload) && isset($payload['pushedAt'])) {
            $pushedAt = $payload['pushedAt'];

            if (is_numeric($pushedAt)) {
                $pushedAt = $pushedAt > 1e12 ? $pushedAt / 1000 : (float) $pushedAt;
            } elseif (is_string($pushedAt)) {
                $pushedAt = strtotime($pushedAt);
            }

            if (is_numeric($pushedAt)) {
                $queueWaitMs = round((microtime(true) - (float) $pushedAt) * 1000, 2);
                Cache::put('metal_sync:last_queue_wait_ms', $queueWaitMs, now()->addDays(7));
            }
        }

        $result = $next($job);

        if ($queueWaitMs !== null) {
            Cache::put('metal_sync:last_queue_wait_ms', $queueWaitMs, now()->addDays(7));
        }

        return $result;
    }
}

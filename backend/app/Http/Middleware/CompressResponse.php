<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CompressResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Enable gzip compression for API responses
        if ($request->expectsJson() && !headers_sent()) {
            // Check if client supports gzip
            $acceptEncoding = $request->header('Accept-Encoding', '');
            if (strpos($acceptEncoding, 'gzip') !== false) {
                $response->header('Content-Encoding', 'gzip');

                // Compress the content if not already compressed
                if (!$response->headers->has('Content-Encoding')) {
                    $content = $response->getContent();
                    if ($content && strlen($content) > 1024) { // Only compress larger responses
                        $compressed = gzencode($content, 6);
                        if ($compressed !== false) {
                            $response->setContent($compressed);
                            $response->header('Content-Encoding', 'gzip');
                        }
                    }
                }
            }
        }

        return $response;
    }
}
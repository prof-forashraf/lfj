<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class RobotsController extends Controller
{
    public function __invoke()
    {
        $lines = ['User-agent: *'];

        foreach (config('seo.robots.disallow', []) as $path) {
            $lines[] = 'Disallow: ' . $path;
        }

        foreach (config('seo.robots.allow', []) as $path) {
            $lines[] = 'Allow: ' . $path;
        }

        $lines[] = 'Sitemap: ' . rtrim(config('seo.site_url'), '/') . '/sitemap.xml';

        foreach (config('seo.robots.blocked_agents', []) as $agent) {
            $lines[] = '';
            $lines[] = 'User-agent: ' . $agent;
            $lines[] = 'Disallow: /';
        }

        return response(Str::finish(implode("\n", $lines), "\n"), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}

<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('sitemap:generate')->dailyAt('02:00');
Schedule::command('metal:sync-prices')->twiceDaily(9, 15)
    ->timezone('America/New_York')
    ->withoutOverlapping()
    ->runInBackground()
    ->onOneServer();
Schedule::command('metal:prune-prices')
    ->dailyAt('02:30')
    ->timezone('America/New_York')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground();

// Lightweight product feature helper command
Artisan::command('products:feature {--count=8} {--ids=} {--dry-run}', function () {
    $idsOpt = $this->option('ids');
    $dry = $this->option('dry-run');

    if ($idsOpt) {
        $ids = array_filter(array_map('trim', explode(',', $idsOpt)));
        $this->info('Marking explicit IDs as featured: ' . implode(', ', $ids));
        if ($dry) {
            $this->line('Dry run: no DB changes.');
            return;
        }
        $updated = \App\Models\AffiliateProduct::whereIn('id', $ids)->update(['is_featured' => 1]);
        $this->info("Updated $updated rows.");
        return;
    }

    $count = (int) $this->option('count');
    if ($count <= 0) {
        $this->error('Count must be a positive integer');
        return;
    }

    $this->info("Selecting top $count active products to mark featured...");

    $products = \App\Models\AffiliateProduct::where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->limit($count)
        ->get(['id']);

    $ids = $products->pluck('id')->toArray();
    if (empty($ids)) {
        $this->info('No active products found to mark.');
        return;
    }

    $this->info('Selected IDs: ' . implode(', ', $ids));
    if ($dry) {
        $this->line('Dry run: no DB changes.');
        return;
    }

    $updated = \App\Models\AffiliateProduct::whereIn('id', $ids)->update(['is_featured' => 1]);
    $this->info("Updated $updated rows.");
});

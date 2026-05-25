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

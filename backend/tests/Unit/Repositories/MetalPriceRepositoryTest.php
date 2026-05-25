<?php

use App\Models\DailyMetalPrice;
use App\Models\GoldPrice;
use App\Repositories\DailyMetalPriceRepository;
use App\Repositories\GoldPriceRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('daily metal price repository upserts and returns latest values for each symbol', function () {
    $repository = new DailyMetalPriceRepository();

    $repository->upsert('2026-05-21', 'USD', 'XAU', 2023.12, 'ounce');
    $repository->upsert('2026-05-22', 'USD', 'XAU', 2030.40, 'ounce');
    $repository->upsert('2026-05-22', 'USD', 'XAG', 23.18, 'ounce');

    $latestXau = $repository->latest('XAU', 'USD');
    $latestXag = $repository->latest('XAG', 'USD');

    expect($latestXau)->not->toBeNull();
    expect($latestXau->price_per_unit)->toBe(2030.40);
    expect($latestXau->unit)->toBe('ounce');
    expect($latestXag)->not->toBeNull();
    expect($latestXag->price_per_unit)->toBe(23.18);

    $latest = $repository->latestForSymbols(['XAG', 'XAU'], 'USD');
    expect($latest->pluck('metal_symbol')->sort()->values()->all())->toEqual(['XAG', 'XAU']);
});

test('gold price repository upserts and returns latest gold entry', function () {
    $repository = new GoldPriceRepository();

    $repository->upsert([
        'date_recorded' => '2026-05-21',
        'currency_code' => 'USD',
        'price_per_ounce' => 2020.75,
        'price_per_gram_24k' => 2020.75 / 31.1035,
        'price_per_gram_22k' => (2020.75 / 31.1035) * (22 / 24),
        'price_per_gram_18k' => (2020.75 / 31.1035) * (18 / 24),
        'price_per_gram_14k' => (2020.75 / 31.1035) * (14 / 24),
        'price_per_gram_10k' => (2020.75 / 31.1035) * (10 / 24),
        'source' => 'UnitTest',
        'is_active' => true,
    ]);

    $repository->upsert([
        'date_recorded' => '2026-05-22',
        'currency_code' => 'USD',
        'price_per_ounce' => 2031.50,
        'price_per_gram_24k' => 2031.50 / 31.1035,
        'price_per_gram_22k' => (2031.50 / 31.1035) * (22 / 24),
        'price_per_gram_18k' => (2031.50 / 31.1035) * (18 / 24),
        'price_per_gram_14k' => (2031.50 / 31.1035) * (14 / 24),
        'price_per_gram_10k' => (2031.50 / 31.1035) * (10 / 24),
        'source' => 'UnitTest',
        'is_active' => true,
    ]);

    $latest = $repository->latest('USD');

    expect($latest)->not->toBeNull();
    expect($latest->price_per_ounce)->toBe(2031.50);
    expect($latest->currency_code)->toBe('USD');
});

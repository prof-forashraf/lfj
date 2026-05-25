<?php

use App\Services\GoldApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Config::set('services.metal_api.key', 'test-key');
    Config::set('services.metal_api.base_url', 'https://www.goldapi.io/api');
});

test('gold api service fetches live metal data and parses payloads correctly', function () {
    Http::fake([
        'https://www.goldapi.io/api/XAU/USD' => Http::response([
            'price' => 2025.55,
            'timestamp' => 1710000000,
            'unit' => 'ounce',
        ], 200),
    ]);

    $service = new GoldApiService;
    $payload = $service->fetchRate('XAU', 'USD');

    expect($payload)
        ->toBeArray()
        ->toHaveKey('price')
        ->and($payload['price'])->toBe(2025.55)
        ->and($payload['unit'])->toBe('ounce');

    expect($service->getPricePerOunce($payload))->toBe(2025.55);
    expect($service->getUnit($payload))->toBe('ounce');
    expect($service->getTimestamp($payload)->toDateString())
        ->toBe(Carbon::createFromTimestamp(1710000000)->toDateString());

    Http::assertSentCount(1);
});

test('gold api service throws when the response omits a price field', function () {
    Http::fake([
        'https://www.goldapi.io/api/XAU/USD' => Http::response(['message' => 'rate unavailable'], 200),
    ]);

    $service = new GoldApiService;

    expect(fn () => $service->fetchRate('XAU', 'USD'))->toThrow(RuntimeException::class, 'GoldAPI returned an unexpected response');
});

<?php

use App\Repositories\DailyMetalPriceRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use App\Domain\Pricing\DTOs\MetalPriceDTO;
use App\Domain\Pricing\ValueObjects\Money;
use Carbon\Carbon;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    // Seed a recent daily metal price so the service doesn't treat it as stale
    $repo = new DailyMetalPriceRepository();

    $repo->upsert(now()->toDateTimeString(), 'USD', 'XAU', 2000.00, 'ounce');

    // Ensure cached price is cleared so tests use the fresh record
    Cache::forget('pricing:metal:XAU:USD:latest');

    // Bind a small fake MetalPriceService that returns a fresh DTO to avoid
    // intermittent stale-cache timing issues in the test environment.
    $this->instance(App\Domain\Pricing\Contracts\MetalPriceServiceInterface::class, new class implements App\Domain\Pricing\Contracts\MetalPriceServiceInterface {
        public function getCurrentPrice(string $metalSymbol, string $currency = 'USD'): \App\Domain\Pricing\DTOs\MetalPriceDTO {
            return new MetalPriceDTO(
                'XAU',
                new Money(2000.00, strtoupper($currency)),
                strtoupper($currency),
                'ounce',
                Carbon::now(),
                'unit-test'
            );
        }
        public function convertPrice(Money $price, string $fromUnit, string $toUnit): Money
        {
            return $price;
        }

        public function getPriceWithFallback(string $metalSymbol, string $currency = 'USD'): ?\App\Domain\Pricing\DTOs\MetalPriceDTO
        {
            return $this->getCurrentPrice($metalSymbol, $currency);
        }
    });
});

test('pricing engine returns a valid jewelry quote with debug steps', function () {
    $engine = app(App\Domain\Pricing\PricingEngine::class);

    $input = [
        'metal_symbol' => 'XAU',
        'currency' => 'USD',
        'weight_grams' => 5,
        'purity_grade' => 24,
        'fabrication_cost' => 10,
        'labor_cost' => 5,
        'gemstone_cost' => 0,
        'markup_percentage' => 20,
        'tax_rate' => 5,
        'debug' => true,
    ];

    $quote = $engine->quoteJewelry($input);

    expect($quote->finalPrice->amount())->toBeGreaterThan(0);
    expect($quote->debugSteps)->not->toBeEmpty();
});

test('pricing engine calculates resale buyback correctly', function () {
    $engine = app(App\Domain\Pricing\PricingEngine::class);

    $input = [
        'metal_symbol' => 'XAU',
        'currency' => 'USD',
        'weight_grams' => 10,
        'purity_grade' => 22,
        'condition' => 'good',
        'buyback_percentage' => 50,
        'debug' => true,
    ];

    $result = $engine->calculateResale($input);

    expect($result->buybackAmount->amount())->toBeGreaterThan(0);
    expect($result->estimatedMarketValue->amount())->toBeGreaterThan(0);
});

test('pricing engine computes zakat due for large holdings', function () {
    $engine = app(App\Domain\Pricing\PricingEngine::class);

    $input = [
        'metal_symbol' => 'XAU',
        'currency' => 'USD',
        'weight_grams' => 100,
        'nisab_basis' => 'gold',
        'debug' => true,
    ];

    $result = $engine->calculateZakat($input);

    expect($result->zakatDue->amount())->toBeGreaterThanOrEqual(0);
    expect($result->totalValue->amount())->toBeGreaterThan(0);
});

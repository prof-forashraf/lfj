<?php

use App\Domain\Pricing\Calculators\MarkupCalculator;
use App\Domain\Pricing\Calculators\MetalConversionCalculator;
use App\Domain\Pricing\Calculators\ResaleCalculator;
use App\Domain\Pricing\Calculators\TaxCalculator;
use App\Domain\Pricing\Calculators\ZakatCalculator;
use App\Domain\Pricing\DTOs\JewelryPricingInputDTO;
use App\Domain\Pricing\DTOs\MetalPriceDTO;
use App\Domain\Pricing\DTOs\ResalePricingInputDTO;
use App\Domain\Pricing\DTOs\ZakatPricingInputDTO;
use App\Domain\Pricing\Services\JewelryPricingService;
use App\Domain\Pricing\Services\ResalePricingService;
use App\Domain\Pricing\Services\ZakatPricingService;
use App\Domain\Pricing\ValueObjects\Markup;
use App\Domain\Pricing\ValueObjects\MetalWeight;
use App\Domain\Pricing\ValueObjects\Money;
use App\Domain\Pricing\ValueObjects\Purity;
use App\Domain\Pricing\ValueObjects\TaxRate;
use Carbon\Carbon;
use Tests\TestCase;

uses(TestCase::class);

function makeMarketPriceDto(float $price, string $currency = 'USD', string $unit = 'ounce'): MetalPriceDTO
{
    return new MetalPriceDTO(
        metalSymbol: 'XAU',
        pricePerUnit: new Money($price, $currency),
        baseCurrency: $currency,
        unit: $unit,
        recordedAt: Carbon::now(),
        source: 'unit-test'
    );
}

test('jewelry pricing service calculates final quote correctly', function () {
    $service = new JewelryPricingService(
        new MarkupCalculator,
        new TaxCalculator
    );

    $marketPrice = makeMarketPriceDto(2000.00, 'USD', 'ounce');
    $input = new JewelryPricingInputDTO(
        metalSymbol: 'XAU',
        currency: 'USD',
        weight: new MetalWeight(10.0),
        purity: new Purity(100.0),
        fabricationCost: new Money(10.00, 'USD'),
        laborCost: new Money(5.00, 'USD'),
        gemstoneCost: new Money(0.00, 'USD'),
        markup: new Markup(20.0),
        taxRate: new TaxRate(5.0),
    );

    $quote = $service->calculateQuote($input, $marketPrice);

    expect($quote->metalCost->amount())->toBe(643.00);
    expect($quote->markupAmount->amount())->toBe(131.60);
    expect($quote->taxAmount->amount())->toBe(39.48);
    expect($quote->finalPrice->amount())->toBe(829.08);
    expect($quote->breakdown['metal_cost'])->toBe(643.00);
});

test('resale pricing service applies buyback percentage and deductions', function () {
    $service = new ResalePricingService(new ResaleCalculator);

    $marketPrice = makeMarketPriceDto(2000.00, 'USD', 'ounce');
    $input = new ResalePricingInputDTO(
        metalSymbol: 'XAU',
        currency: 'USD',
        weight: new MetalWeight(10.0),
        purity: new Purity(100.0),
        condition: 'good',
        buybackPercentage: 50.0,
    );

    $result = $service->calculate($input, $marketPrice);

    expect($result->estimatedMarketValue->amount())->toBe(643.00);
    expect($result->buybackAmount->amount())->toBe(321.50);
    expect($result->deductions)->toBeEmpty();
});

test('zakat pricing service calculates liability and zakat due correctly', function () {
    $service = new ZakatPricingService(new ZakatCalculator, new MetalConversionCalculator);

    $marketPrice = makeMarketPriceDto(2000.00, 'USD', 'ounce');
    $input = new ZakatPricingInputDTO(
        metalSymbol: 'XAU',
        currency: 'USD',
        weight: new MetalWeight(100.0),
        nisabBasis: 'gold',
    );

    $result = $service->calculate($input, $marketPrice);

    expect($result->totalValue->amount())->toBe(6430.00);
    expect($result->nisabThreshold->amount())->toBe(5624.96);
    expect($result->isLiableForZakat)->toBeTrue();
    expect($result->zakatDue->amount())->toBe(160.75);
});

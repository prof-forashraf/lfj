<?php

namespace App\Domain\Pricing\Services;

use App\Domain\Pricing\Calculators\MetalConversionCalculator;
use App\Domain\Pricing\Contracts\MetalPriceServiceInterface;
use App\Domain\Pricing\DTOs\MetalPriceDTO;
use App\Domain\Pricing\Enums\MetalUnit;
use App\Domain\Pricing\Exceptions\StaleMarketPriceException;
use App\Domain\Pricing\ValueObjects\Money;
use App\Models\DailyMetalPrice;
use App\Models\GoldPrice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class MetalPriceService implements MetalPriceServiceInterface
{
    private const CACHE_TTL_MINUTES = 360; // 6 hours

    public function __construct(private MetalConversionCalculator $conversionCalculator) {}

    public function getCurrentPrice(string $metalSymbol, string $currency = 'USD'): MetalPriceDTO
    {
        $price = $this->getPriceWithFallback($metalSymbol, $currency);

        if (! $price) {
            throw StaleMarketPriceException::noRecentPrice($metalSymbol, $currency);
        }

        if ($this->isStale($price)) {
            throw StaleMarketPriceException::noRecentPrice($metalSymbol, $currency);
        }

        return $price;
    }

    public function getPriceWithFallback(string $metalSymbol, string $currency = 'USD'): ?MetalPriceDTO
    {
        $key = $this->cacheKey($metalSymbol, $currency);

        $cached = Cache::get($key);
        if ($cached instanceof MetalPriceDTO) {
            return $cached;
        }

        $price = $this->loadLatestPrice($metalSymbol, $currency);

        if ($price) {
            Cache::put($key, $price, now()->addMinutes(self::CACHE_TTL_MINUTES));
        }

        return $price;
    }

    public function convertPrice(Money $price, string $fromUnit, string $toUnit): Money
    {
        return $this->conversionCalculator->convertPrice($price, $fromUnit, $toUnit);
    }

    private function isStale(MetalPriceDTO $price): bool
    {
        $thresholdMinutes = config('services.metal_sync.stale_threshold_minutes', 30);

        return $price->recordedAt->diffInMinutes(now()) > $thresholdMinutes;
    }

    private function cacheKey(string $symbol, string $currency): string
    {
        return sprintf('pricing:metal:%s:%s:latest', strtoupper($symbol), strtoupper($currency));
    }

    private function loadLatestPrice(string $metalSymbol, string $currency): ?MetalPriceDTO
    {
        $daily = DailyMetalPrice::query()
            ->where('metal_symbol', strtoupper($metalSymbol))
            ->where('base_currency', strtoupper($currency))
            ->orderByDesc('price_date')
            ->first();

        if ($daily) {
            return $this->mapDailyMetalPrice($daily);
        }

        if (strtoupper($metalSymbol) === 'XAU') {
            $gold = GoldPrice::query()
                ->where('currency_code', strtoupper($currency))
                ->orderByDesc('date_recorded')
                ->first();

            if ($gold) {
                return new MetalPriceDTO(
                    metalSymbol: 'XAU',
                    pricePerUnit: new Money((float) $gold->price_per_ounce, strtoupper($currency)),
                    baseCurrency: strtoupper($currency),
                    unit: MetalUnit::OUNCE->value,
                    recordedAt: Carbon::parse($gold->date_recorded),
                    source: 'gold_prices',
                );
            }
        }

        return null;
    }

    private function mapDailyMetalPrice(DailyMetalPrice $daily): MetalPriceDTO
    {
        return new MetalPriceDTO(
            metalSymbol: strtoupper($daily->metal_symbol),
            pricePerUnit: new Money((float) $daily->price_per_unit, strtoupper($daily->base_currency)),
            baseCurrency: strtoupper($daily->base_currency),
            unit: $daily->unit ?: MetalUnit::OUNCE->value,
            recordedAt: Carbon::parse($daily->price_date),
            source: $daily->source ?? 'daily_metal_prices',
            staleSinceAt: null,
        );
    }
}

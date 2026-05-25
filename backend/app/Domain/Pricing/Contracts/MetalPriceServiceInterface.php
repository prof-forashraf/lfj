<?php

namespace App\Domain\Pricing\Contracts;

use App\Domain\Pricing\DTOs\MetalPriceDTO;
use App\Domain\Pricing\Exceptions\StaleMarketPriceException;
use App\Domain\Pricing\ValueObjects\Money;

interface MetalPriceServiceInterface
{
    /**
     * Get current market price for a metal.
     *
     * @throws StaleMarketPriceException if no recent price available
     */
    public function getCurrentPrice(string $metalSymbol, string $currency = 'USD'): MetalPriceDTO;

    /**
     * Convert metal price from one unit to another.
     * Example: convert price per ounce to price per gram
     */
    public function convertPrice(Money $price, string $fromUnit, string $toUnit): Money;

    /**
     * Get price with stale-cache fallback tolerance.
     * Returns cached price even if old, with staleness indicator.
     */
    public function getPriceWithFallback(string $metalSymbol, string $currency = 'USD'): ?MetalPriceDTO;
}

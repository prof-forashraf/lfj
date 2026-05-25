<?php

namespace App\Domain\Pricing\Contracts;

use App\Domain\Pricing\DTOs\MetalPriceDTO;

interface MetalPriceRepositoryInterface
{
    /**
     * Get the latest market price for a metal.
     *
     * @param  string  $symbol  Metal symbol (e.g., 'XAU' for gold)
     * @param  string  $currency  Currency code (e.g., 'USD')
     * @param  int  $maxStaleMins  Maximum age of cached price in minutes
     */
    public function getLatestPrice(string $symbol, string $currency = 'USD', int $maxStaleMins = 60): ?MetalPriceDTO;

    /**
     * Get historical prices for a metal within a date range.
     */
    public function getPriceHistory(string $symbol, string $currency, \DateTime $from, \DateTime $to): array;

    /**
     * Store a market price snapshot.
     */
    public function store(MetalPriceDTO $price): void;
}

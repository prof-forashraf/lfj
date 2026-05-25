<?php

namespace App\Domain\Pricing\Exceptions;

class StaleMarketPriceException extends PricingException
{
    public static function noRecentPrice(string $metal, string $currency): self
    {
        return new self("No recent market price for {$metal}/{$currency}");
    }

    public static function circuitBreakerOpen(): self
    {
        return new self('Market API circuit breaker is open');
    }
}

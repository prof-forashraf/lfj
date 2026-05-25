<?php

namespace App\Domain\Pricing\Exceptions;

class InvalidPriceException extends PricingException
{
    public static function negative(): self
    {
        return new self('Price cannot be negative');
    }

    public static function zero(): self
    {
        return new self('Price cannot be zero');
    }

    public static function tooLarge(): self
    {
        return new self('Price exceeds maximum allowed value');
    }
}

<?php

namespace App\Domain\Pricing\Exceptions;

class InvalidPurityException extends PricingException
{
    public static function outOfRange(float $purity): self
    {
        return new self("Purity {$purity} is outside valid range 0-100");
    }

    public static function unsupported(string $grade): self
    {
        return new self("Purity grade '{$grade}' is not supported");
    }
}

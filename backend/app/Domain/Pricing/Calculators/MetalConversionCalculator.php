<?php

namespace App\Domain\Pricing\Calculators;

use App\Domain\Pricing\Enums\MetalUnit;
use App\Domain\Pricing\ValueObjects\Money;

final class MetalConversionCalculator
{
    private const GRAMS_PER_TROY_OUNCE = 31.1035;

    public function convertPrice(Money $price, string $fromUnit, string $toUnit): Money
    {
        if ($fromUnit === $toUnit) {
            return $price;
        }

        if ($fromUnit === MetalUnit::OUNCE->value && $toUnit === MetalUnit::GRAM->value) {
            return new Money($price->amount() / self::GRAMS_PER_TROY_OUNCE, $price->currency());
        }

        if ($fromUnit === MetalUnit::GRAM->value && $toUnit === MetalUnit::OUNCE->value) {
            return new Money($price->amount() * self::GRAMS_PER_TROY_OUNCE, $price->currency());
        }

        if ($fromUnit === MetalUnit::TROY_OUNCE->value && $toUnit === MetalUnit::GRAM->value) {
            return new Money($price->amount() / self::GRAMS_PER_TROY_OUNCE, $price->currency());
        }

        if ($fromUnit === MetalUnit::GRAM->value && $toUnit === MetalUnit::TROY_OUNCE->value) {
            return new Money($price->amount() * self::GRAMS_PER_TROY_OUNCE, $price->currency());
        }

        throw new \InvalidArgumentException(sprintf('Unsupported conversion from %s to %s', $fromUnit, $toUnit));
    }
}

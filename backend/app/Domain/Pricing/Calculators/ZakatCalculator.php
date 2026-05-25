<?php

namespace App\Domain\Pricing\Calculators;

use App\Domain\Pricing\ValueObjects\Money;

final class ZakatCalculator
{
    private const ZAKAT_RATE = 2.5;

    private const GOLD_NISAB_GRAMS = 87.48;

    private const SILVER_NISAB_GRAMS = 612.35;

    public function calculateNisabThreshold(Money $pricePerGram, string $basis = 'gold'): Money
    {
        $grams = $basis === 'silver' ? self::SILVER_NISAB_GRAMS : self::GOLD_NISAB_GRAMS;

        return new Money($pricePerGram->amount() * $grams, $pricePerGram->currency());
    }

    public function calculateZakat(Money $totalValue): Money
    {
        return new Money($totalValue->amount() * (self::ZAKAT_RATE / 100), $totalValue->currency());
    }

    public function isLiable(Money $totalValue, Money $nisabThreshold): bool
    {
        return $totalValue->amount() >= $nisabThreshold->amount();
    }
}

<?php

namespace App\Domain\Pricing\Calculators;

use App\Domain\Pricing\ValueObjects\Money;

final class ResaleCalculator
{
    public function calculateDeductions(Money $marketValue, float $deductionPercentage): Money
    {
        return new Money($marketValue->amount() * ($deductionPercentage / 100), $marketValue->currency());
    }

    public function calculateBuyback(Money $marketValue, float $buybackPercentage, float $deductionPercentage = 0): Money
    {
        $buybackAmount = $marketValue->amount() * ($buybackPercentage / 100);
        $deductions = $marketValue->amount() * ($deductionPercentage / 100);

        return new Money(max($buybackAmount - $deductions, 0), $marketValue->currency());
    }
}

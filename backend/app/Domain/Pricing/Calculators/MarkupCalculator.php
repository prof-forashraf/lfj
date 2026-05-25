<?php

namespace App\Domain\Pricing\Calculators;

use App\Domain\Pricing\ValueObjects\Markup;
use App\Domain\Pricing\ValueObjects\Money;

final class MarkupCalculator
{
    public function calculateAmount(Money $baseCost, Markup $markup): Money
    {
        return new Money($baseCost->amount() * ($markup->percentage() / 100), $baseCost->currency());
    }

    public function apply(Money $baseCost, Markup $markup): Money
    {
        return $baseCost->add($this->calculateAmount($baseCost, $markup));
    }
}

<?php

namespace App\Domain\Pricing\Calculators;

use App\Domain\Pricing\ValueObjects\Money;
use App\Domain\Pricing\ValueObjects\TaxRate;

final class TaxCalculator
{
    public function calculate(Money $subtotal, TaxRate $rate): Money
    {
        return $rate->calculate($subtotal);
    }

    public function apply(Money $subtotal, TaxRate $rate): Money
    {
        return $rate->addToTotal($subtotal);
    }
}

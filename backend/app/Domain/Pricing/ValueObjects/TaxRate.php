<?php

namespace App\Domain\Pricing\ValueObjects;

final class TaxRate
{
    private float $percentage;

    public function __construct(float $percentage = 0.0)
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException('Tax rate must be between 0 and 100');
        }

        $this->percentage = round($percentage, 2);
    }

    public function percentage(): float
    {
        return $this->percentage;
    }

    public function calculate(Money $subtotal): Money
    {
        $taxAmount = $subtotal->amount() * ($this->percentage / 100);

        return new Money($taxAmount, $subtotal->currency());
    }

    public function addToTotal(Money $subtotal): Money
    {
        $tax = $this->calculate($subtotal);

        return $subtotal->add($tax);
    }

    public function toString(): string
    {
        return sprintf('%.2f%%', $this->percentage);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}

<?php

namespace App\Domain\Pricing\ValueObjects;

final class Markup
{
    private float $percentage;

    public function __construct(float $percentage = 0.0)
    {
        if ($percentage < 0) {
            throw new \InvalidArgumentException('Markup percentage cannot be negative');
        }

        $this->percentage = round($percentage, 2);
    }

    public function percentage(): float
    {
        return $this->percentage;
    }

    public function apply(Money $baseCost): Money
    {
        $markupAmount = $baseCost->amount() * ($this->percentage / 100);

        return new Money($baseCost->amount() + $markupAmount, $baseCost->currency());
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

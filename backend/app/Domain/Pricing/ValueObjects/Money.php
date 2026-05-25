<?php

namespace App\Domain\Pricing\ValueObjects;

use App\Domain\Pricing\Exceptions\InvalidPriceException;

final class Money
{
    private float $amount;

    private string $currency;

    public function __construct(float $amount, string $currency = 'USD')
    {
        if ($amount < 0) {
            throw InvalidPriceException::negative();
        }

        if ($amount > 999_999_999.99) {
            throw InvalidPriceException::tooLarge();
        }

        $this->amount = round($amount, 2);
        $this->currency = $currency;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function add(Money $other): Money
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot add Money in different currencies');
        }

        return new Money($this->amount + $other->amount, $this->currency);
    }

    public function multiply(float $factor): Money
    {
        return new Money($this->amount * $factor, $this->currency);
    }

    public function divide(float $divisor): Money
    {
        if ($divisor == 0) {
            throw new \DivisionByZeroError('Cannot divide Money by zero');
        }

        return new Money($this->amount / $divisor, $this->currency);
    }

    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    public function greaterThan(Money $other): bool
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot compare Money in different currencies');
        }

        return $this->amount > $other->amount;
    }

    public function toString(): string
    {
        return sprintf('%s %.2f', $this->currency, $this->amount);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}

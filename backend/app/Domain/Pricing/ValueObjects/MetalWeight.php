<?php

namespace App\Domain\Pricing\ValueObjects;

final class MetalWeight
{
    private float $grams;

    private const GRAMS_PER_OUNCE = 31.1035;

    private const GRAMS_PER_TROY_OUNCE = 31.1035;

    private const GRAMS_PER_TOLA = 11.66;

    public function __construct(float $grams)
    {
        if ($grams < 0) {
            throw new \InvalidArgumentException('Weight cannot be negative');
        }

        $this->grams = round($grams, 4);
    }

    public static function fromOunces(float $ounces): self
    {
        return new self($ounces * self::GRAMS_PER_OUNCE);
    }

    public static function fromTroyOunces(float $ounces): self
    {
        return new self($ounces * self::GRAMS_PER_TROY_OUNCE);
    }

    public static function fromTola(float $tola): self
    {
        return new self($tola * self::GRAMS_PER_TOLA);
    }

    public function grams(): float
    {
        return $this->grams;
    }

    public function ounces(): float
    {
        return round($this->grams / self::GRAMS_PER_OUNCE, 4);
    }

    public function troyOunces(): float
    {
        return round($this->grams / self::GRAMS_PER_TROY_OUNCE, 4);
    }

    public function tola(): float
    {
        return round($this->grams / self::GRAMS_PER_TOLA, 4);
    }

    public function add(MetalWeight $other): MetalWeight
    {
        return new MetalWeight($this->grams + $other->grams);
    }

    public function multiply(float $factor): MetalWeight
    {
        return new MetalWeight($this->grams * $factor);
    }

    public function toString(): string
    {
        return sprintf('%.4f g', $this->grams);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}

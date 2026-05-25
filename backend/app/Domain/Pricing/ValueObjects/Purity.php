<?php

namespace App\Domain\Pricing\ValueObjects;

use App\Domain\Pricing\Exceptions\InvalidPurityException;

final class Purity
{
    private float $percentage;

    private ?string $grade;

    private const COMMON_GRADES = [
        '999' => 99.9,
        '995' => 99.5,
        '990' => 99.0,
        '925' => 92.5, // Sterling silver
        '900' => 90.0,
        '875' => 87.5,
        '800' => 80.0,
        '750' => 75.0, // 18K gold
        '720' => 72.0,
        '585' => 58.5, // 14K gold
        '417' => 41.7, // 10K gold
        '375' => 37.5, // 9K gold
    ];

    public function __construct(float $percentage, ?string $grade = null)
    {
        if ($percentage < 0 || $percentage > 100) {
            throw InvalidPurityException::outOfRange($percentage);
        }

        $this->percentage = round($percentage, 2);
        $this->grade = $grade;
    }

    public static function fromGrade(string $grade): self
    {
        if (! isset(self::COMMON_GRADES[$grade])) {
            throw InvalidPurityException::unsupported($grade);
        }

        return new self(self::COMMON_GRADES[$grade], $grade);
    }

    public function percentage(): float
    {
        return $this->percentage;
    }

    public function grade(): ?string
    {
        return $this->grade;
    }

    public function pureWeight(MetalWeight $totalWeight): MetalWeight
    {
        $pureGrams = $totalWeight->grams() * ($this->percentage / 100);

        return new MetalWeight($pureGrams);
    }

    public function wastePercentage(): float
    {
        return 100 - $this->percentage;
    }

    public function toString(): string
    {
        if ($this->grade) {
            return sprintf('%s (%d%%)', $this->grade, (int) $this->percentage);
        }

        return sprintf('%.2f%%', $this->percentage);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}

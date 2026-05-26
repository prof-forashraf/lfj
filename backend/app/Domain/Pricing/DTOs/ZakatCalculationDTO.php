<?php

namespace App\Domain\Pricing\DTOs;

use App\Domain\Pricing\ValueObjects\MetalWeight;
use App\Domain\Pricing\ValueObjects\Money;

/**
 * @template T of ZakatCalculationDTO
 */
class ZakatCalculationDTO
{
    public function __construct(
        public readonly MetalWeight $holdingWeight,
        public readonly Money $nisabThreshold,
        public readonly Money $totalValue,
        public readonly bool $isLiableForZakat,
        public readonly Money $zakatDue,
        public readonly array $debugSteps = [],
    ) {}

    public function withDebugSteps(array $debugSteps): static
    {
        return new static(
            holdingWeight: $this->holdingWeight,
            nisabThreshold: $this->nisabThreshold,
            totalValue: $this->totalValue,
            isLiableForZakat: $this->isLiableForZakat,
            zakatDue: $this->zakatDue,
            debugSteps: $debugSteps,
        );
    }

    public function toArray(): array
    {
        return [
            'holding_weight_grams' => $this->holdingWeight->grams(),
            'nisab_threshold' => $this->nisabThreshold->amount(),
            'total_value' => $this->totalValue->amount(),
            'is_liable' => $this->isLiableForZakat,
            'zakat_due' => $this->zakatDue->amount(),
            'currency' => $this->nisabThreshold->currency(),
            'debug_steps' => $this->debugSteps,
        ];
    }
}

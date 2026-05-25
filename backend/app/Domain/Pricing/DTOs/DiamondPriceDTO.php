<?php

namespace App\Domain\Pricing\DTOs;

use App\Domain\Pricing\ValueObjects\Money;

final class DiamondPriceDTO
{
    public function __construct(
        public readonly Money $pricePerCarat,
        public readonly string $cutGrade,
        public readonly string $colorGrade,
        public readonly string $clarityGrade,
    ) {}

    public function toArray(): array
    {
        return [
            'price_per_carat' => $this->pricePerCarat->amount(),
            'currency' => $this->pricePerCarat->currency(),
            'cut_grade' => $this->cutGrade,
            'color_grade' => $this->colorGrade,
            'clarity_grade' => $this->clarityGrade,
        ];
    }
}

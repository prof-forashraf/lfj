<?php

namespace App\Domain\Pricing\DTOs;

use App\Domain\Pricing\ValueObjects\Money;

final class GemstonePriceDTO
{
    public function __construct(
        public readonly Money $pricePerUnit,
        public readonly string $unit,
        public readonly string $gemstoneType,
        public readonly string $qualityGrade,
    ) {}

    public function toArray(): array
    {
        return [
            'price_per_unit' => $this->pricePerUnit->amount(),
            'currency' => $this->pricePerUnit->currency(),
            'unit' => $this->unit,
            'gemstone_type' => $this->gemstoneType,
            'quality_grade' => $this->qualityGrade,
        ];
    }
}

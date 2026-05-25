<?php

namespace App\Domain\Pricing\DTOs;

use App\Domain\Pricing\ValueObjects\MetalWeight;
use App\Domain\Pricing\ValueObjects\Purity;

final class ResalePricingInputDTO
{
    public function __construct(
        public readonly string $metalSymbol,
        public readonly string $currency,
        public readonly MetalWeight $weight,
        public readonly Purity $purity,
        public readonly string $condition,
        public readonly float $buybackPercentage,
        public readonly bool $debug = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            metalSymbol: strtoupper(trim($data['metal_symbol'] ?? 'XAU')),
            currency: strtoupper(trim($data['currency'] ?? 'USD')),
            weight: new MetalWeight((float) ($data['weight_grams'] ?? 0)),
            purity: is_numeric($data['purity_grade'] ?? '')
                ? new Purity((float) $data['purity_grade'])
                : Purity::fromGrade((string) ($data['purity_grade'] ?? '')),
            condition: strtolower(trim($data['condition'] ?? 'good')),
            buybackPercentage: isset($data['buyback_percentage']) ? (float) $data['buyback_percentage'] : 50.0,
            debug: isset($data['debug']) ? filter_var($data['debug'], FILTER_VALIDATE_BOOLEAN) : false,
        );
    }
}

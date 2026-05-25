<?php

namespace App\Domain\Pricing\DTOs;

use App\Domain\Pricing\ValueObjects\MetalWeight;

final class ZakatPricingInputDTO
{
    public function __construct(
        public readonly string $metalSymbol,
        public readonly string $currency,
        public readonly MetalWeight $weight,
        public readonly string $nisabBasis,
        public readonly bool $debug = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            metalSymbol: strtoupper(trim($data['metal_symbol'] ?? 'XAU')),
            currency: strtoupper(trim($data['currency'] ?? 'USD')),
            weight: new MetalWeight((float) ($data['weight_grams'] ?? 0)),
            nisabBasis: strtolower(trim($data['nisab_basis'] ?? 'gold')),
            debug: isset($data['debug']) ? filter_var($data['debug'], FILTER_VALIDATE_BOOLEAN) : false,
        );
    }
}

<?php

namespace App\Domain\Pricing\DTOs;

use App\Domain\Pricing\ValueObjects\Markup;
use App\Domain\Pricing\ValueObjects\MetalWeight;
use App\Domain\Pricing\ValueObjects\Money;
use App\Domain\Pricing\ValueObjects\Purity;
use App\Domain\Pricing\ValueObjects\TaxRate;

final class JewelryPricingInputDTO
{
    public function __construct(
        public readonly string $metalSymbol,
        public readonly string $currency,
        public readonly MetalWeight $weight,
        public readonly Purity $purity,
        public readonly Money $fabricationCost,
        public readonly Money $laborCost,
        public readonly Money $gemstoneCost,
        public readonly Markup $markup,
        public readonly TaxRate $taxRate,
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
            fabricationCost: new Money((float) ($data['fabrication_cost'] ?? 0), strtoupper(trim($data['currency'] ?? 'USD'))),
            laborCost: new Money((float) ($data['labor_cost'] ?? 0), strtoupper(trim($data['currency'] ?? 'USD'))),
            gemstoneCost: new Money((float) ($data['gemstone_cost'] ?? 0), strtoupper(trim($data['currency'] ?? 'USD'))),
            markup: new Markup((float) ($data['markup_percentage'] ?? 0)),
            taxRate: new TaxRate((float) ($data['tax_rate'] ?? 0)),
            debug: isset($data['debug']) ? filter_var($data['debug'], FILTER_VALIDATE_BOOLEAN) : false,
        );
    }
}

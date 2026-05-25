<?php

namespace App\Domain\Pricing\Services;

use App\Domain\Pricing\Calculators\MetalConversionCalculator;
use App\Domain\Pricing\Calculators\ZakatCalculator;
use App\Domain\Pricing\Contracts\ZakatPricingServiceInterface;
use App\Domain\Pricing\DTOs\MetalPriceDTO;
use App\Domain\Pricing\DTOs\ZakatDTO;
use App\Domain\Pricing\DTOs\ZakatPricingInputDTO;
use App\Domain\Pricing\ValueObjects\Money;

class ZakatPricingService implements ZakatPricingServiceInterface
{
    public function __construct(
        private ZakatCalculator $zakatCalculator,
        private MetalConversionCalculator $conversionCalculator,
    ) {}

    public function calculate(ZakatPricingInputDTO $input, MetalPriceDTO $marketPrice): ZakatDTO
    {
        $marketPricePerGram = $this->conversionCalculator->convertPrice(
            $marketPrice->pricePerUnit,
            $marketPrice->unit,
            'gram'
        );

        $totalValue = new Money($marketPricePerGram->amount() * $input->weight->grams(), $input->currency);
        $nisabThreshold = $this->zakatCalculator->calculateNisabThreshold($marketPricePerGram, $input->nisabBasis);
        $isLiable = $this->zakatCalculator->isLiable($totalValue, $nisabThreshold);
        $zakatDue = $isLiable ? $this->zakatCalculator->calculateZakat($totalValue) : new Money(0.0, $input->currency);

        return new ZakatDTO(
            holdingWeight: $input->weight,
            nisabThreshold: $nisabThreshold,
            totalValue: $totalValue,
            isLiableForZakat: $isLiable,
            zakatDue: $zakatDue,
        );
    }
}

<?php

namespace App\Domain\Pricing\Services;

use App\Domain\Pricing\Calculators\MetalConversionCalculator;
use App\Domain\Pricing\Calculators\ResaleCalculator;
use App\Domain\Pricing\Contracts\ResalePricingServiceInterface;
use App\Domain\Pricing\DTOs\MetalPriceDTO;
use App\Domain\Pricing\DTOs\ResaleDTO;
use App\Domain\Pricing\DTOs\ResalePricingInputDTO;
use App\Domain\Pricing\ValueObjects\Money;

class ResalePricingService implements ResalePricingServiceInterface
{
    public function __construct(
        private ResaleCalculator $resaleCalculator,
    ) {}

    public function calculate(ResalePricingInputDTO $input, MetalPriceDTO $marketPrice): ResaleDTO
    {
        $marketPricePerGram = $this->convertToGram($marketPrice);
        $pureWeight = $input->purity->pureWeight($input->weight);
        $estimatedMarketValue = new Money($marketPricePerGram->amount() * $pureWeight->grams(), $input->currency);

        $deductionPercent = 0.0;
        $deductions = [];

        if ($input->condition === 'damaged') {
            $deductionPercent += 15.0;
            $deductions[] = 'damaged item adjustment';
        }

        if ($input->purity->percentage() < 90) {
            $deductionPercent += 5.0;
            $deductions[] = 'purity deduction';
        }

        $buybackAmount = $this->resaleCalculator->calculateBuyback($estimatedMarketValue, $input->buybackPercentage, $deductionPercent);

        return new ResaleDTO(
            originalPrice: new Money($estimatedMarketValue->amount(), $input->currency),
            estimatedMarketValue: $estimatedMarketValue,
            buybackAmount: $buybackAmount,
            buybackPercentage: $input->buybackPercentage,
            condition: $input->condition,
            deductions: $deductions,
        );
    }

    private function convertToGram(MetalPriceDTO $marketPrice): Money
    {
        $calculator = new MetalConversionCalculator;

        return $calculator->convertPrice($marketPrice->pricePerUnit, $marketPrice->unit, 'gram');
    }
}

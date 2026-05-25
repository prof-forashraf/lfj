<?php

namespace App\Domain\Pricing\Services;

use App\Domain\Pricing\Calculators\MarkupCalculator;
use App\Domain\Pricing\Calculators\MetalConversionCalculator;
use App\Domain\Pricing\Calculators\TaxCalculator;
use App\Domain\Pricing\Contracts\JewelryPricingServiceInterface;
use App\Domain\Pricing\DTOs\JewelryPricingInputDTO;
use App\Domain\Pricing\DTOs\JewelryQuoteDTO;
use App\Domain\Pricing\DTOs\MetalPriceDTO;
use App\Domain\Pricing\ValueObjects\Money;

class JewelryPricingService implements JewelryPricingServiceInterface
{
    public function __construct(
        private MarkupCalculator $markupCalculator,
        private TaxCalculator $taxCalculator,
    ) {}

    public function calculateQuote(JewelryPricingInputDTO $input, MetalPriceDTO $marketPrice): JewelryQuoteDTO
    {
        $marketPricePerGram = $this->convertToGram($marketPrice);

        $pureWeight = $input->purity->pureWeight($input->weight);
        $metalCost = new Money($marketPricePerGram->amount() * $pureWeight->grams(), $input->currency);

        $subtotal = $metalCost
            ->add($input->fabricationCost)
            ->add($input->laborCost)
            ->add($input->gemstoneCost);

        $markupAmount = $this->markupCalculator->calculateAmount($subtotal, $input->markup);
        $subtotalWithMarkup = $subtotal->add($markupAmount);
        $taxAmount = $this->taxCalculator->calculate($subtotalWithMarkup, $input->taxRate);
        $finalPrice = $subtotalWithMarkup->add($taxAmount);

        return new JewelryQuoteDTO(
            metalCost: $metalCost,
            fabricationCost: $input->fabricationCost,
            laborCost: $input->laborCost,
            gemstoneCost: $input->gemstoneCost,
            markupAmount: $markupAmount,
            subtotal: $subtotalWithMarkup,
            taxAmount: $taxAmount,
            finalPrice: $finalPrice,
            breakdown: [
                'metal_cost' => $metalCost->amount(),
                'fabrication_cost' => $input->fabricationCost->amount(),
                'labor_cost' => $input->laborCost->amount(),
                'gemstone_cost' => $input->gemstoneCost->amount(),
                'markup_amount' => $markupAmount->amount(),
                'tax_amount' => $taxAmount->amount(),
            ],
        );
    }

    private function convertToGram(MetalPriceDTO $marketPrice): Money
    {
        $calculator = new MetalConversionCalculator;

        return $calculator->convertPrice($marketPrice->pricePerUnit, $marketPrice->unit, 'gram');
    }
}

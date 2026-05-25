<?php

namespace App\Domain\Pricing\Contracts;

use App\Domain\Pricing\DTOs\JewelryPricingInputDTO;
use App\Domain\Pricing\DTOs\JewelryQuoteDTO;
use App\Domain\Pricing\DTOs\MetalPriceDTO;

interface JewelryPricingServiceInterface
{
    /**
     * Calculate complete jewelry pricing with all components.
     */
    public function calculateQuote(JewelryPricingInputDTO $input, MetalPriceDTO $marketPrice): JewelryQuoteDTO;
}

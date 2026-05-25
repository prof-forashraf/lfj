<?php

namespace App\Domain\Pricing\Contracts;

use App\Domain\Pricing\DTOs\MetalPriceDTO;
use App\Domain\Pricing\DTOs\ResaleDTO;
use App\Domain\Pricing\DTOs\ResalePricingInputDTO;

interface ResalePricingServiceInterface
{
    public function calculate(ResalePricingInputDTO $input, MetalPriceDTO $marketPrice): ResaleDTO;
}

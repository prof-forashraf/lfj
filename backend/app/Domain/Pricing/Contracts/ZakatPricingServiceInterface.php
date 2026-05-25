<?php

namespace App\Domain\Pricing\Contracts;

use App\Domain\Pricing\DTOs\MetalPriceDTO;
use App\Domain\Pricing\DTOs\ZakatDTO;
use App\Domain\Pricing\DTOs\ZakatPricingInputDTO;

interface ZakatPricingServiceInterface
{
    public function calculate(ZakatPricingInputDTO $input, MetalPriceDTO $marketPrice): ZakatDTO;
}

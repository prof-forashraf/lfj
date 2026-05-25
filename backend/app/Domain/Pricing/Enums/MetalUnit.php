<?php

namespace App\Domain\Pricing\Enums;

enum MetalUnit: string
{
    case OUNCE = 'ounce';
    case GRAM = 'gram';
    case TROY_OUNCE = 'troy_ounce';
}

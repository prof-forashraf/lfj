<?php

namespace App\Domain\Pricing\Enums;

enum PricingComponent: string
{
    case METAL_COST = 'metal_cost';
    case FABRICATION_COST = 'fabrication_cost';
    case LABOR_COST = 'labor_cost';
    case GEMSTONE_COST = 'gemstone_cost';
    case MARKUP = 'markup';
    case TAX = 'tax';
}

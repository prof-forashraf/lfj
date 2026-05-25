<?php

namespace App\Domain\Pricing\Contracts;

interface PricingPipelineStep
{
    public function handle(array $payload): array;
}

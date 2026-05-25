<?php

namespace App\Domain\Pricing\Pipelines;

use App\Domain\Pricing\Contracts\PricingPipelineStep;

final class PricingPipeline
{
    /**
     * @param  PricingPipelineStep[]  $steps
     */
    public function __construct(private array $steps = []) {}

    public function pipe(PricingPipelineStep $step): self
    {
        $this->steps[] = $step;

        return $this;
    }

    public function execute(array $payload): array
    {
        foreach ($this->steps as $step) {
            $payload = $step->handle($payload);
        }

        return $payload;
    }
}

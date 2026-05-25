<?php

namespace App\Domain\Pricing;

use App\Domain\Pricing\Contracts\JewelryPricingServiceInterface;
use App\Domain\Pricing\Contracts\MetalPriceServiceInterface;
use App\Domain\Pricing\Contracts\ResalePricingServiceInterface;
use App\Domain\Pricing\Contracts\ZakatPricingServiceInterface;
use App\Domain\Pricing\DTOs\JewelryPricingInputDTO;
use App\Domain\Pricing\DTOs\JewelryQuoteDTO;
use App\Domain\Pricing\DTOs\MetalPriceDTO;
use App\Domain\Pricing\DTOs\ResaleDTO;
use App\Domain\Pricing\DTOs\ResalePricingInputDTO;
use App\Domain\Pricing\DTOs\ZakatDTO;
use App\Domain\Pricing\DTOs\ZakatPricingInputDTO;

final class PricingEngine
{
    public const VERSION = '1.0';

    public function __construct(
        private MetalPriceServiceInterface $metalPriceService,
        private JewelryPricingServiceInterface $jewelryPricingService,
        private ResalePricingServiceInterface $resalePricingService,
        private ZakatPricingServiceInterface $zakatPricingService,
    ) {}

    public function quoteJewelry(array $input): JewelryQuoteDTO
    {
        $payload = [
            'input' => JewelryPricingInputDTO::fromArray($input),
            'debugSteps' => [],
        ];

        $payload = $this->runPipeline([
            [$this, 'resolveMarketPrice'],
            [$this, 'calculateJewelryQuote'],
            [$this, 'attachDebugSteps'],
        ], $payload);

        return $payload['output'];
    }

    public function calculateResale(array $input): ResaleDTO
    {
        $payload = [
            'input' => ResalePricingInputDTO::fromArray($input),
            'debugSteps' => [],
        ];

        $payload = $this->runPipeline([
            [$this, 'resolveMarketPrice'],
            [$this, 'calculateResaleValue'],
            [$this, 'attachDebugSteps'],
        ], $payload);

        return $payload['output'];
    }

    public function calculateZakat(array $input): ZakatDTO
    {
        $payload = [
            'input' => ZakatPricingInputDTO::fromArray($input),
            'debugSteps' => [],
        ];

        $payload = $this->runPipeline([
            [$this, 'resolveMarketPrice'],
            [$this, 'calculateZakatValue'],
            [$this, 'attachDebugSteps'],
        ], $payload);

        return $payload['output'];
    }

    public function getMetalPrice(string $symbol, string $currency): MetalPriceDTO
    {
        return $this->metalPriceService->getCurrentPrice(strtoupper($symbol), strtoupper($currency));
    }

    private function runPipeline(array $steps, array $payload): array
    {
        foreach ($steps as $step) {
            $payload = $step($payload);
        }

        return $payload;
    }

    private function resolveMarketPrice(array $payload): array
    {
        /** @var JewelryPricingInputDTO|ResalePricingInputDTO|ZakatPricingInputDTO $input */
        $input = $payload['input'];
        $marketPrice = $this->metalPriceService->getCurrentPrice($input->metalSymbol, $input->currency);

        $payload['marketPrice'] = $marketPrice;
        $payload['debugSteps'][] = [
            'step' => 'market_price_resolution',
            'metal_symbol' => $input->metalSymbol,
            'currency' => $input->currency,
            'price_per_unit' => $marketPrice->pricePerUnit->amount(),
            'unit' => $marketPrice->unit,
            'source' => $marketPrice->source,
            'recorded_at' => $marketPrice->recordedAt->toISOString(),
        ];

        return $payload;
    }

    private function calculateJewelryQuote(array $payload): array
    {
        /** @var JewelryPricingInputDTO $input */
        $input = $payload['input'];
        $marketPrice = $payload['marketPrice'];
        $quote = $this->jewelryPricingService->calculateQuote($input, $marketPrice);

        $payload['debugSteps'] = array_merge($payload['debugSteps'], [
            ['step' => 'cost_calculation', 'metal_cost' => $quote->metalCost->amount()],
            ['step' => 'markup_application', 'markup_amount' => $quote->markupAmount->amount()],
            ['step' => 'tax_application', 'tax_amount' => $quote->taxAmount->amount()],
        ]);

        $payload['output'] = $quote;

        return $payload;
    }

  private function calculateResaleValue(array $payload): array
    {
        /** @var ResalePricingInputDTO $input */
        $input = $payload['input'];
        $marketPrice = $payload['marketPrice'];
        $result = $this->resalePricingService->calculate($input, $marketPrice);

        $payload['debugSteps'] = array_merge($payload['debugSteps'], [
            ['step' => 'resale_calculation', 'estimated_market_value' => $result->estimatedMarketValue->amount()],
            ['step' => 'buyback_amount', 'buyback_amount' => $result->buybackAmount->amount()],
            ['step' => 'deductions', 'deductions' => $result->deductions],
        ]);

        if (method_exists(ResaleDTO::class, 'fromCalculation')) {
            $payload['output'] = ResaleDTO::fromCalculation($result);
        } elseif (method_exists(ResaleDTO::class, 'fromArray')) {
            $payload['output'] = ResaleDTO::fromArray((array) $result);
        } else {
            // FIX: Match the exact positional arguments of the DTO constructor
            $payload['output'] = new ResaleDTO(
                $result->originalPrice,
                $result->estimatedMarketValue,
                $result->buybackAmount,
                $result->buybackPercentage,
                $result->deductions
            );
        }

        return $payload;
    }

    private function calculateZakatValue(array $payload): array
    {
        /** @var ZakatPricingInputDTO $input */
        $input = $payload['input'];
        $marketPrice = $payload['marketPrice'];
        $result = $this->zakatPricingService->calculate($input, $marketPrice);

        $payload['debugSteps'] = array_merge($payload['debugSteps'], [
            ['step' => 'zakat_threshold', 'nisab_threshold' => $result->nisabThreshold->amount()],
            ['step' => 'total_value', 'total_value' => $result->totalValue->amount()],
            ['step' => 'zakat_due', 'zakat_due' => $result->zakatDue->amount()],
        ]);

        if (method_exists(ZakatDTO::class, 'fromCalculation')) {
            $payload['output'] = ZakatDTO::fromCalculation($result);
        } elseif (method_exists(ZakatDTO::class, 'fromArray')) {
            $payload['output'] = ZakatDTO::fromArray((array) $result);
        } else {
            // FIX: Match the exact positional arguments of the DTO constructor
            $payload['output'] = new ZakatDTO(
                $result->holdingWeight,
                $result->nisabThreshold,
                $result->totalValue,
                $result->isLiableForZakat,
                $result->zakatDue
            );
        }

        return $payload;
    }
    private function attachDebugSteps(array $payload): array
    {
        if (! $payload['input']->debug) {
            return $payload;
        }

        if (isset($payload['output']) && method_exists($payload['output'], 'withDebugSteps')) {
            $payload['output'] = $payload['output']->withDebugSteps($payload['debugSteps']);
        }

        return $payload;
    }
}
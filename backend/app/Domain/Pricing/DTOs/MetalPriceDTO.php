<?php

namespace App\Domain\Pricing\DTOs;

use App\Domain\Pricing\ValueObjects\Money;
use Carbon\Carbon;

class MetalPriceDTO
{
    public function __construct(
        public readonly string $metalSymbol,
        public readonly Money $pricePerUnit,
        public readonly string $baseCurrency,
        public readonly string $unit,
        public readonly Carbon $recordedAt,
        public readonly string $source,
        public readonly ?Carbon $staleSinceAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            metalSymbol: $data['metal_symbol'] ?? throw new \InvalidArgumentException('metal_symbol required'),
            pricePerUnit: new Money($data['price_per_unit'] ?? 0, $data['currency'] ?? 'USD'),
            baseCurrency: $data['currency'] ?? 'USD',
            unit: $data['unit'] ?? 'ounce',
            recordedAt: $data['recorded_at'] instanceof Carbon ? $data['recorded_at'] : Carbon::parse($data['recorded_at'] ?? now()),
            source: $data['source'] ?? 'API',
            staleSinceAt: isset($data['stale_since']) ? Carbon::parse($data['stale_since']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'metal_symbol' => $this->metalSymbol,
            'price_per_unit' => $this->pricePerUnit->amount(),
            'currency' => $this->baseCurrency,
            'unit' => $this->unit,
            'recorded_at' => $this->recordedAt,
            'source' => $this->source,
            'stale_since' => $this->staleSinceAt,
        ];
    }
}

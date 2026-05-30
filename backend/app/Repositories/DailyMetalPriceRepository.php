<?php

namespace App\Repositories;

use App\Models\DailyMetalPrice;
use Illuminate\Support\Collection;

class DailyMetalPriceRepository
{
    public function upsert(string $priceDate, string $currency, string $symbol, float $pricePerUnit, string $unit = 'ounce'): DailyMetalPrice
    {
        try {
            return DailyMetalPrice::updateOrCreate(
                [
                    'price_date' => $priceDate,
                    'base_currency' => strtoupper($currency),
                    'metal_symbol' => strtoupper($symbol),
                ],
                [
                    'price_per_unit' => $pricePerUnit,
                    'unit' => $unit,
                ]
            );
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle rare duplicate-key race by returning the existing record.
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return DailyMetalPrice::where('price_date', $priceDate)
                    ->where('base_currency', strtoupper($currency))
                    ->where('metal_symbol', strtoupper($symbol))
                    ->first();
            }

            throw $e;
        }
    }

    public function latestForSymbols(array $symbols, string $currency = 'USD'): Collection
    {
        return DailyMetalPrice::whereIn('metal_symbol', array_map('strtoupper', $symbols))
            ->where('base_currency', strtoupper($currency))
            ->orderByDesc('price_date')
            ->get()
            ->unique('metal_symbol')
            ->values();
    }

    public function latest(string $symbol, string $currency = 'USD'): ?DailyMetalPrice
    {
        return DailyMetalPrice::where('metal_symbol', strtoupper($symbol))
            ->where('base_currency', strtoupper($currency))
            ->orderByDesc('price_date')
            ->first();
    }
}

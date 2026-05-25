<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JewelryMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MaterialPriceController extends Controller
{
    /**
     * Fetch the latest price for a given metal from the jewelry_materials table.
     * This is a private helper function used by the public API methods.
     * Changed to use an exact match for better accuracy.
     */
    private function getMetalPricePerGram(string $metalName, string $currency): ?float
    {
        $material = JewelryMaterial::where('material_type', 'Metal')
            ->where('material_name', $metalName) // Use exact match instead of LIKE
            ->where('currency_code', $currency)
            ->where('is_active', 1)
            ->latest('date_updated')
            ->first();

        return $material ? $material->price_per_unit : null;
    }

    /**
     * API endpoint to get the latest gold price, standardized to 24K.
     */
    public function getGoldPrice(Request $request, string $currency = 'USD'): JsonResponse
    {
        try {
            $currency = strtoupper($currency ?: 'USD');
            $payload = $this->resolveMetalPricePayload('XAU', $currency);

            if (!is_array($payload) || !isset($payload['price_per_unit'])) {
                Log::warning("No synchronized gold price found for currency {$currency}");

                return response()->json(['error' => 'No synchronized gold price data available.'], 404);
            }

            return $this->buildCurrencyResponse('XAU', (float) $payload['price_per_unit'], $payload, $currency);
        } catch (\Exception $e) {
            Log::error("Error in getGoldPrice: " . $e->getMessage());
            return response()->json(['error' => 'An internal server error occurred.'], 500);
        }
    }

    public function getSilverPrice(Request $request, string $currency = 'USD'): JsonResponse
    {
        try {
            $currency = strtoupper($currency ?: 'USD');
            $payload = $this->resolveMetalPricePayload('XAG', $currency);

            if (!is_array($payload) || !isset($payload['price_per_unit'])) {
                Log::warning("No synchronized silver price found for currency {$currency}");

                return response()->json(['error' => 'No synchronized silver price data available.'], 404);
            }

            return $this->buildCurrencyResponse('XAG', (float) $payload['price_per_unit'], $payload, $currency);
        } catch (\Exception $e) {
            Log::error("Error in getSilverPrice: " . $e->getMessage());
            return response()->json(['error' => 'An internal server error occurred.'], 500);
        }
    }

    private function resolveMetalPricePayload(string $symbol, string $currency): ?array
    {
        $cacheKey = "metal_prices:{$symbol}:{$currency}:latest";
        $cached = Cache::get($cacheKey);

        if (is_array($cached) && isset($cached['price_per_unit'])) {
            $cached['source'] = 'cache';
            return $cached;
        }

        $daily = DB::table('daily_metal_prices')
            ->where('metal_symbol', $symbol)
            ->where('base_currency', $currency)
            ->orderByDesc('price_date')
            ->first();

        if ($daily && isset($daily->price_per_unit)) {
            $payload = [
                'symbol' => $symbol,
                'currency' => $currency,
                'price_per_unit' => (float) $daily->price_per_unit,
                'unit' => $daily->unit ?? 'ounce',
                'price_date' => $daily->price_date,
                'timestamp' => Carbon::parse($daily->price_date)->toISOString(),
                'source' => 'db',
            ];

            Cache::put($cacheKey, $payload, now()->addHours(6));

            return $payload;
        }

        if ($symbol === 'XAU') {
            $gold = DB::table('gold_prices')
                ->where('currency_code', $currency)
                ->orderByDesc('date_recorded')
                ->first();

            if ($gold && isset($gold->price_per_ounce)) {
                $payload = [
                    'symbol' => $symbol,
                    'currency' => $currency,
                    'price_per_unit' => (float) $gold->price_per_ounce,
                    'unit' => 'ounce',
                    'price_date' => $gold->date_recorded,
                    'timestamp' => Carbon::parse($gold->date_recorded)->toISOString(),
                    'source' => 'gold_prices',
                ];

                Cache::put($cacheKey, $payload, now()->addHours(6));

                return $payload;
            }
        }

        return null;
    }

    private function buildCurrencyResponse(string $symbol, float $pricePerOunce, array $cached, string $currency): JsonResponse
    {
        return response()->json([
            'success' => true,
            'rates' => [$symbol => 1 / $pricePerOunce],
            'meta' => [
                'cached_at' => $cached['timestamp'] ?? now()->toISOString(),
                'price_source' => $cached['source'] ?? 'cache',
                'is_stale' => $this->isCachedPriceStale($cached),
                'last_successful_sync' => Cache::get('metal_sync:last_success'),
            ],
        ]);
    }

    private function isCachedPriceStale(array $cached): bool
    {
        $thresholdMinutes = config('services.metal_sync.stale_threshold_minutes', 30);
        $cachedAt = isset($cached['timestamp']) ? Carbon::parse($cached['timestamp']) : null;
        $lastSuccess = Cache::get('metal_sync:last_success');
        $lastSuccessAt = $lastSuccess ? Carbon::parse($lastSuccess) : null;

        if ($cachedAt && $cachedAt->diffInMinutes(now()) > $thresholdMinutes) {
            return true;
        }

        if ($lastSuccessAt && $lastSuccessAt->diffInMinutes(now()) > $thresholdMinutes) {
            return true;
        }

        return false;
    }
}
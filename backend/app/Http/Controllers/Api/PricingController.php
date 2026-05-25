<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Pricing\JewelryQuoteRequest;
use App\Http\Requests\Api\Pricing\ResalePricingRequest;
use App\Http\Requests\Api\Pricing\ZakatPricingRequest;
use App\Domain\Pricing\PricingEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PricingController extends Controller
{
    public function __construct(private PricingEngine $pricingEngine)
    {
    }

    public function quote(JewelryQuoteRequest $request): JsonResponse
    {
        try {
            $quote = $this->pricingEngine->quoteJewelry($request->validated());
            $payload = [
                'success' => true,
                'pricing_version' => PricingEngine::VERSION,
                'data' => $quote->toArray(),
            ];

            if ($request->boolean('debug')) {
                $payload['debug'] = true;
            }

            return response()->json($payload);
        } catch (\Throwable $e) {
            Log::error('Pricing quote failed: ' . $e->getMessage(), ['payload' => $request->validated()]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function resale(ResalePricingRequest $request): JsonResponse
    {
        try {
            $result = $this->pricingEngine->calculateResale($request->validated());
            $payload = [
                'success' => true,
                'pricing_version' => PricingEngine::VERSION,
                'data' => $result->toArray(),
            ];

            if ($request->boolean('debug')) {
                $payload['debug'] = true;
            }

            return response()->json($payload);
        } catch (\Throwable $e) {
            Log::error('Resale pricing calculation failed: ' . $e->getMessage(), ['payload' => $request->validated()]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function zakat(ZakatPricingRequest $request): JsonResponse
    {
        try {
            $result = $this->pricingEngine->calculateZakat($request->validated());
            $payload = [
                'success' => true,
                'pricing_version' => PricingEngine::VERSION,
                'data' => $result->toArray(),
            ];

            if ($request->boolean('debug')) {
                $payload['debug'] = true;
            }

            return response()->json($payload);
        } catch (\Throwable $e) {
            Log::error('Zakat pricing calculation failed: ' . $e->getMessage(), ['payload' => $request->validated()]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function metalPrice(Request $request, string $symbol, string $currency): JsonResponse
    {
        try {
            $metalPrice = $this->pricingEngine->getMetalPrice($symbol, $currency);
            $payload = [
                'success' => true,
                'pricing_version' => PricingEngine::VERSION,
                'data' => $metalPrice->toArray(),
            ];

            if ($request->boolean('debug')) {
                $payload['debug'] = true;
            }

            return response()->json($payload);
        } catch (\Throwable $e) {
            Log::error('Metal pricing fetch failed: ' . $e->getMessage(), ['symbol' => $symbol, 'currency' => $currency]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

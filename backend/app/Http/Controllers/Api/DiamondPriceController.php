<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetDiamondPriceRequest;
use App\Models\DiamondPricing;
use Illuminate\Http\JsonResponse;

class DiamondPriceController extends Controller
{
    /**
     * Fetch the base price per carat for a diamond based on its specifications.
     */
    public function getPrice(GetDiamondPriceRequest $request): JsonResponse
    {
        // ✅ FIX: Use FormRequest for validation (auto-validates on controller injection)
        $caratWeight = (float) $request->validated('carat');

        // Find the most recent, active price record that matches the diamond's specs.
        $priceRecord = DiamondPricing::where('is_active', 1)
            ->where('carat_range_min', '<=', $caratWeight)
            ->where('carat_range_max', '>=', $caratWeight)
            ->where('cut_grade', $request->validated('cut'))
            ->where('color_grade', $request->validated('color'))
            ->where('clarity_grade', $request->validated('clarity'))
            ->latest('date_updated') // Get the most up-to-date price
            ->first();

        if (!$priceRecord) {
            // If no exact match, you could implement a fallback to find the 'closest' match.
            // For now, we return an error.
            return response()->json(['error' => 'No matching price found for the selected specifications. Please try a different combination.'], 404);
        }

        return response()->json([
            'pricePerCarat' => $priceRecord->base_price_per_carat,
        ]);
    }
}

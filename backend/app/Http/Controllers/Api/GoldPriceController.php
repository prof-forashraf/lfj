<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JewelryMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;


class GoldPriceController extends Controller
{
    /**
     * Fetch historical gold price data for a given currency and time range
     * from the centralized jewelry_materials table.
     */
    public function getHistoricalPrices(Request $request, string $currency, string $range = '1M'): JsonResponse
    {
        $query = JewelryMaterial::where('material_type', 'Metal')
            ->where('material_name', 'like', '%22K Yellow Gold%')
            ->where('currency_code', $currency)
            ->where('is_active', 1);

        if ($request->has(['start_date', 'end_date'])) {
            try {
                $startDate = Carbon::parse($request->query('start_date'));
                $endDate = Carbon::parse($request->query('end_date'));
                $query->whereBetween('date_updated', [$startDate->toDateString(), $endDate->toDateString()]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid date format provided.'], 400);
            }
        } else {
            $endDate = Carbon::today();
            $startDate = match ($range) {
                '7D' => $endDate->copy()->subDays(7),
                '3M' => $endDate->copy()->subMonths(3),
                '1Y' => $endDate->copy()->subYear(),
                default => $endDate->copy()->subMonth(),
            };
            $query->whereBetween('date_updated', [$startDate->toDateString(), $endDate->toDateString()]);
        }

        $historicalData = $query->orderBy('date_updated', 'asc')
            ->get(['date_updated', 'price_per_unit']);

        if ($historicalData->isEmpty()) {
            return response()->json(['data' => []]);
        }

        $formattedData = $historicalData->map(function ($price) {
            $pricePerOunce = $price->price_per_unit * 31.1035;

            // --- CORRECTED LINE ---
            // $price->date_updated is already a Carbon object due to model casting.
            // We can format it directly without re-parsing it.
            $formattedDate = $price->date_updated->format('d M Y');

            return [
                'date' => $formattedDate,
                'price' => (float) $pricePerOunce,
            ];
        });

        return response()->json(['data' => $formattedData]);
    }
}
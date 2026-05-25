<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GemstonePricing;
use Illuminate\Http\JsonResponse;

class MaterialController extends Controller
{
    /**
     * Provide a list of available metals for the calculator.
     * In a real application, this could also come from a database table.
     */
    public function getMetals(): JsonResponse
    {
        $metals = [
            ['value' => '14k-gold', 'label' => '14K Gold'],
            ['value' => '18k-gold', 'label' => '18K Gold'],
            ['value' => '22k-gold', 'label' => '22K Gold'],
            ['value' => 'platinum', 'label' => 'Platinum (PT950)'],
            ['value' => 'silver', 'label' => 'Sterling Silver (925)'],
        ];

        return response()->json($metals);
    }

    /**
     * Provide a list of available gemstones and their base prices from the database.
     */
    public function getGemstones(): JsonResponse
    {
        $gemstones = GemstonePricing::where('is_active', true)
            ->orderBy('name')
            ->get(['name as label', 'slug as value', 'price_per_carat']);

        return response()->json($gemstones);
    }
}

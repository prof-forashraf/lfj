<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomJewelryDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomJewelryDesignController extends Controller
{
    public function index()
    {
        $designs = Auth::user()->customJewelryDesigns()->latest()->get();
        return response()->json($designs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'jewelry_type' => 'required|string',
            'materials' => 'required|array',
            'customizations' => 'array',
        ]);

        $design = CustomJewelryDesign::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'jewelry_type' => $request->jewelry_type,
            'materials' => $request->materials,
            'customizations' => $request->customizations,
            'estimated_price' => $this->calculatePrice($request->materials, $request->customizations),
        ]);

        return response()->json($design, 201);
    }

    public function show(CustomJewelryDesign $design)
    {
        $this->authorize('view', $design);
        return response()->json($design);
    }

    public function update(Request $request, CustomJewelryDesign $design)
    {
        $this->authorize('update', $design);

        $request->validate([
            'name' => 'string|max:255',
            'materials' => 'array',
            'customizations' => 'array',
        ]);

        $design->update($request->only(['name', 'materials', 'customizations']));
        $design->estimated_price = $this->calculatePrice($design->materials, $design->customizations);
        $design->save();

        return response()->json($design);
    }

    public function destroy(CustomJewelryDesign $design)
    {
        $this->authorize('delete', $design);
        $design->delete();
        return response()->json(['message' => 'Design deleted']);
    }

    private function calculatePrice(array $materials, array $customizations = []): float
    {
        $basePrices = [
            'gold' => 50,
            'silver' => 5,
            'platinum' => 80,
            'diamond' => 100,
            'emerald' => 75,
        ];

        $price = 0;

        foreach ($materials as $material => $details) {
            if (isset($basePrices[$material])) {
                $price += $basePrices[$material] * ($details['quantity'] ?? 1);
            }
        }

        // Add customization costs
        if (isset($customizations['engraving'])) {
            $price += 25;
        }

        return $price;
    }
}

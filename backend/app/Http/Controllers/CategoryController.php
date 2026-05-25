<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category; // <-- Assumes you have this Model for your categories table
use App\Models\AffiliateProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories with their LIVE product counts.
     */
    public function index()
    {
        // Step 1: Get all the official categories from your `categories` table.
        // We will assume a 'status' column exists to only show active categories.
        // If not, you can remove ->where('status', 'published').
        $categories = Category::where('status', 'published')->orderBy('name')->get();

        // Step 2: Get all the LIVE product counts from the affiliate_products table,
        // grouped by their item_type. Using keyBy() makes lookups instant.
        $productCounts = AffiliateProduct::query()
            ->select('item_type', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->whereNotNull('item_type')
            ->groupBy('item_type')
            ->get()
            ->keyBy('item_type'); // Creates an associative array, e.g., ['earrings' => {count: 10}]

        // Step 3: Merge the counts into the category data.
        $formattedCategories = $categories->map(function ($category) use ($productCounts) {
            // Make the lookup key consistent (e.g., lowercase slug)
            $lookupKey = strtolower($category->slug);

            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image,
                // Look up the count. If it exists, use it. Otherwise, default to 0.
                'productCount' => $productCounts[$lookupKey]->count ?? 0,
            ];
        });

        return response()->json(['data' => $formattedCategories]);
    }

    /**
     * Display the specified category with its associated products.
     */
    public function show(Request $request, string $slug)
    {
        // Find all active products matching the item_type (slug)
        $products = AffiliateProduct::where('status', 'active')
            ->where('item_type', $slug)
            ->orderBy('updated_at', 'desc')
            ->paginate($request->input('per_page', 12));

        return new \App\Http\Resources\AffiliateProductCollection($products);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffiliateProduct;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdvancedSearchController extends Controller
{
    /**
     * Advanced search with AI-powered suggestions
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $category = $request->get('category');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $material = $request->get('material');
        $gemstone = $request->get('gemstone');
        $itemType = $request->get('item_type');
        $isFeatured = $request->boolean('featured');
        $sortBy = $request->get('sort', 'relevance');
        $perPage = min($request->get('per_page', 20), 100);

        // Build the search query
        $searchQuery = AffiliateProduct::search($query);

        // Apply filters
        if ($category) {
            $searchQuery->where('category_slug', $category);
        }

        if ($minPrice || $maxPrice) {
            $searchQuery->whereBetween('price', [
                $minPrice ?: 0,
                $maxPrice ?: PHP_INT_MAX
            ]);
        }

        if ($material) {
            $searchQuery->where('material', $material);
        }

        if ($gemstone) {
            $searchQuery->where('gemstone', $gemstone);
        }

        if ($itemType) {
            $searchQuery->where('item_type', $itemType);
        }

        if ($isFeatured) {
            $searchQuery->where('is_featured', true);
        }

        // Apply sorting
        switch ($sortBy) {
            case 'price_low':
                $searchQuery->orderBy('price', 'asc');
                break;
            case 'price_high':
                $searchQuery->orderBy('price', 'desc');
                break;
            case 'newest':
                $searchQuery->orderBy('created_at', 'desc');
                break;
            case 'relevance':
            default:
                // Scout handles relevance by default
                break;
        }

        $results = $searchQuery->paginate($perPage);

        // Add AI-powered suggestions
        $suggestions = $this->generateSearchSuggestions($query, $results);

        return response()->json([
            'data' => $results->items(),
            'meta' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'from' => $results->firstItem(),
                'to' => $results->lastItem(),
            ],
            'suggestions' => $suggestions,
            'facets' => $this->getSearchFacets($query),
        ]);
    }

    /**
     * Get autocomplete suggestions
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $limit = min($request->get('limit', 10), 20);

        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        // Get product name suggestions
        $productSuggestions = AffiliateProduct::search($query)
            ->take($limit)
            ->get()
            ->pluck('product_name_snapshot')
            ->unique()
            ->values()
            ->toArray();

        // Get category suggestions as plain strings
        $categorySuggestions = AffiliateProduct::search($query)
            ->take($limit)
            ->get()
            ->map(fn ($product) => optional($product->category)->name)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Get material/gemstone suggestions as plain strings
        $materialSuggestions = AffiliateProduct::search($query)
            ->take($limit)
            ->get()
            ->flatMap(fn ($product) => [
                $product->material,
                $product->gemstone,
            ])
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $combinedSuggestions = array_filter(array_unique(array_merge(
            $productSuggestions,
            $categorySuggestions,
            $materialSuggestions
        )));

        return response()->json([
            'suggestions' => array_slice(array_values($combinedSuggestions), 0, $limit)
        ]);
    }

    /**
     * Generate AI-powered search suggestions
     */
    private function generateSearchSuggestions(string $query, $results): array
    {
        $suggestions = [];

        if (empty($query)) {
            return $suggestions;
        }

        // Popular related searches
        $relatedTerms = $this->getRelatedSearchTerms($query);
        if (!empty($relatedTerms)) {
            $suggestions[] = [
                'type' => 'related',
                'title' => 'Related Searches',
                'items' => array_slice($relatedTerms, 0, 5)
            ];
        }

        // Price range suggestions based on results
        if ($results->count() > 0) {
            $prices = collect($results->items())->pluck('price')->filter();
            if ($prices->count() > 0) {
                $minPrice = $prices->min();
                $maxPrice = $prices->max();
                $avgPrice = $prices->avg();

                $suggestions[] = [
                    'type' => 'price_ranges',
                    'title' => 'Price Ranges',
                    'items' => [
                        "Under $" . number_format($avgPrice * 0.5),
                        "$" . number_format($avgPrice * 0.5) . " - $" . number_format($avgPrice * 1.5),
                        "Over $" . number_format($avgPrice * 1.5)
                    ]
                ];
            }
        }

        // Category suggestions
        $categories = collect($results->items())
            ->pluck('category')
            ->filter()
            ->unique()
            ->values()
            ->take(5);

        if ($categories->count() > 0) {
            $suggestions[] = [
                'type' => 'categories',
                'title' => 'Browse Categories',
                'items' => $categories->toArray()
            ];
        }

        return $suggestions;
    }

    /**
     * Get search facets for filtering
     */
    private function getSearchFacets(string $query): array
    {
        $products = AffiliateProduct::search($query)
            ->take(100)
            ->get();

        return [
            'categories' => $products
                ->map(fn ($product) => optional($product->category)->name)
                ->filter()
                ->unique()
                ->values()
                ->take(10)
                ->toArray(),
            'materials' => $products->pluck('material')->filter()->unique()->values()->take(10)->toArray(),
            'gemstones' => $products->pluck('gemstone')->filter()->unique()->values()->take(10)->toArray(),
            'item_types' => $products->pluck('item_type')->filter()->unique()->values()->take(10)->toArray(),
            'price_ranges' => $this->calculatePriceRanges($products),
        ];
    }

    /**
     * Calculate price ranges for facets
     */
    private function calculatePriceRanges($products): array
    {
        $prices = collect($products)->pluck('price')->filter()->sort();

        if ($prices->isEmpty()) {
            return [];
        }

        $min = $prices->min();
        $max = $prices->max();
        $range = $max - $min;

        if ($range === 0) {
            return ["$" . number_format($min)];
        }

        $step = max(50, ceil($range / 5)); // At least 5 ranges, minimum $50 steps

        $ranges = [];
        for ($i = 0; $i < 5; $i++) {
            $start = $min + ($i * $step);
            $end = min($min + (($i + 1) * $step), $max);

            if ($start >= $max) break;

            if ($i === 4 || $end >= $max) {
                $ranges[] = "$" . number_format($start) . "+";
            } else {
                $ranges[] = "$" . number_format($start) . " - $" . number_format($end);
            }
        }

        return $ranges;
    }

    /**
     * Get related search terms (simplified implementation)
     */
    private function getRelatedSearchTerms(string $query): array
    {
        $terms = [
            'ring' => ['engagement ring', 'wedding ring', 'diamond ring', 'gold ring'],
            'necklace' => ['pendant necklace', 'chain necklace', 'gold necklace', 'diamond necklace'],
            'earrings' => ['diamond earrings', 'gold earrings', 'stud earrings', 'hoop earrings'],
            'bracelet' => ['gold bracelet', 'diamond bracelet', 'tennis bracelet', 'chain bracelet'],
            'diamond' => ['diamond ring', 'diamond necklace', 'diamond earrings', 'diamond bracelet'],
            'gold' => ['gold ring', 'gold necklace', 'gold earrings', 'gold bracelet', '18k gold'],
        ];

        $query = strtolower($query);

        foreach ($terms as $key => $related) {
            if (str_contains($query, $key)) {
                return $related;
            }
        }

        return [];
    }
}
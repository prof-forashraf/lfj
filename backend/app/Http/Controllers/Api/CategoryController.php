<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\AffiliateProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AffiliateProductCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostCollection;


class CategoryController extends Controller
{
    /**
     * Display a listing of all categories, including "orphan" types,
     * with correct product counts and image URLs.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $page = max(1, (int) $request->input('page', 1));
        $perPage = min(100, max(1, (int) $request->input('per_page', 12)));

        $officialCategories = Category::query()
            ->get()
            ->keyBy(fn($cat) => strtolower($cat->slug));

        $productCounts = AffiliateProduct::query()
            ->select('item_type', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->whereNotNull('item_type')
            ->where('item_type', '!=', '')
            ->groupBy('item_type')
            ->get()
            ->keyBy(fn($item) => strtolower($item->item_type));

        $formattedCategories = $productCounts->map(function ($productCount, $itemType) use ($officialCategories) {
            $officialCategory = $officialCategories->get($itemType);

            if ($officialCategory) {
                return [
                    'id' => $officialCategory->id,
                    'name' => $officialCategory->name,
                    'slug' => $officialCategory->slug,
                    'description' => $officialCategory->description,
                    'image' => $officialCategory->image ? Storage::url($officialCategory->image) : null,
                    'productCount' => $productCount->count,
                ];
            }

            return [
                'id' => null,
                'name' => ucfirst($itemType),
                'slug' => $itemType,
                'description' => null,
                'image' => null,
                'productCount' => $productCount->count,
            ];
        });

        $sortedCategories = $formattedCategories->sortBy('name')->values();
        $total = $sortedCategories->count();
        $lastPage = (int) ceil($total / $perPage);
        $currentPage = min($page, max(1, $lastPage));
        $paged = $sortedCategories->forPage($currentPage, $perPage)->values();

        return response()->json([
            'data' => $paged,
            'links' => [
                'first' => $currentPage === 1 ? null : url()->current() . '?page=1&per_page=' . $perPage,
                'last' => $currentPage === $lastPage ? null : url()->current() . '?page=' . $lastPage . '&per_page=' . $perPage,
                'prev' => $currentPage > 1 ? url()->current() . '?page=' . ($currentPage - 1) . '&per_page=' . $perPage : null,
                'next' => $currentPage < $lastPage ? url()->current() . '?page=' . ($currentPage + 1) . '&per_page=' . $perPage : null,
            ],
            'meta' => [
                'current_page' => $currentPage,
                'from' => $total > 0 ? (($currentPage - 1) * $perPage) + 1 : null,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'to' => min($total, $currentPage * $perPage),
                'total' => $total,
            ],
        ]);
    }

    /**
     * Display the specified category details.
     */
    public function show(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category->productCount = AffiliateProduct::where('status', 'active')
            ->where('item_type', $category->slug)
            ->count();

        return new CategoryResource($category);
    }

    /**
     * Display active products for a given category.
     */
    public function products(Request $request, string $slug)
    {
        $products = AffiliateProduct::with('category:id,name,slug')
            ->where('status', 'active')
            ->where('item_type', $slug)
            ->orderBy('updated_at', 'desc')
            ->paginate($request->input('per_page', 12));

        return new AffiliateProductCollection($products);
    }
}
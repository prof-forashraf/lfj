<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    /**
     * Display a listing of the resource for the API.
     * This method handles all public-facing filtering logic.
     */
    public function index(Request $request): PostCollection
    {
        // Start with a base query for published posts
        $query = Post::query()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            // Eager load relationships for optimal performance
            ->with(['author', 'categories', 'tags', 'affiliateProducts.category']);

        // --- FILTERING LOGIC ---

        // 1. Filter by Category Slug (Matches React service: `categorySlug`)
        $query->when($request->input('categorySlug') ?? $request->input('category'), function (Builder $query, $slug) {
            $query->whereHas('categories', function (Builder $catQuery) use ($slug) {
                $catQuery->where('slug', $slug);
            });
        });

        // 2. Filter by Tag Slug (Matches React service: `tagSlug`)
        $query->when($request->input('tagSlug') ?? $request->input('tag'), function (Builder $query, $slug) {
            $query->whereHas('tags', function (Builder $tagQuery) use ($slug) {
                $tagQuery->where('slug', 'like', $slug);
            });
        });

        // 3. Filter for Featured Posts
        $query->when($request->boolean('featured'), function (Builder $query) {
            $query->where('is_featured', true);
        });

        // 4. Exclude a specific post by its ID (Matches React service: `exclude`)
        $query->when($request->input('exclude'), function (Builder $query, $idToExclude) {
            $query->where('id', '!=', $idToExclude);
        });

        // --- ORDERING ---
        $query->orderBy('created_at', 'desc');

        // --- PAGINATION ---
        // This is the key fix. It correctly uses paginate() which reads the ?page param.
        $posts = $query->paginate($request->input('per_page', 9));

        return new PostCollection($posts);
    }

    /**
     * Display the specified resource for the API.
     */
    public function show(Post $post): PostResource
    {
        // Simple check for public-facing posts
        if ($post->status !== 'published' || $post->published_at > now()) {
            abort(404);
        }

        // Eager load relationships
        $post->load(['author', 'categories', 'tags', 'affiliateProducts.category']);

        return new PostResource($post);
    }
}

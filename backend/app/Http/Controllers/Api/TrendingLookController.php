<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrendingLook;
use Illuminate\Http\Request;

class TrendingLookController extends Controller
{
    /**
     * Get trending looks feed
     */
    public function index(Request $request)
    {
        $category = $request->get('category');
        $sort = $request->get('sort', 'trending'); // trending, recent, popular

        $query = TrendingLook::where('status', 'published')
            ->with('user:id,name,avatar_url');

        if ($category) {
            $query->where('category', $category);
        }

        switch ($sort) {
            case 'popular':
                $query->orderByDesc('likes');
                break;
            case 'recent':
                $query->orderByDesc('created_at');
                break;
            default: // trending
                $query->orderByDesc('views')
                    ->orderByDesc('likes')
                    ->where('status', 'trending');
        }

        return response()->json($query->paginate(12));
    }

    /**
     * Create a new trending look
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'required|url',
            'jewelry_items' => 'required|array|min:1',
            'category' => 'nullable|string',
        ]);

        $look = TrendingLook::create([
            'user_id' => auth()->id(),
            ...$validated,
            'status' => 'draft',
        ]);

        return response()->json([
            'message' => 'Look created successfully',
            'data' => $look,
        ], 201);
    }

    /**
     * Get a single look
     */
    public function show(TrendingLook $look)
    {
        $look->incrementViews();
        
        return response()->json($look->load('user:id,name,avatar_url'));
    }

    /**
     * Update a look
     */
    public function update(Request $request, TrendingLook $look)
    {
        // Check ownership
        if ($look->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'jewelry_items' => 'array|min:1',
            'category' => 'nullable|string',
            'status' => 'nullable|in:draft,published,trending',
        ]);

        $look->update($validated);

        return response()->json([
            'message' => 'Look updated successfully',
            'data' => $look,
        ]);
    }

    /**
     * Delete a look
     */
    public function destroy(TrendingLook $look)
    {
        if ($look->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $look->delete();

        return response()->json(['message' => 'Look deleted successfully']);
    }

    /**
     * Like a look
     */
    public function like(Request $request, TrendingLook $look)
    {
        $look->like();

        return response()->json([
            'message' => 'Look liked',
            'likes' => $look->likes,
        ]);
    }

    /**
     * Unlike a look
     */
    public function unlike(Request $request, TrendingLook $look)
    {
        $look->unlike();

        return response()->json([
            'message' => 'Like removed',
            'likes' => $look->likes,
        ]);
    }

    /**
     * Share a look
     */
    public function share(TrendingLook $look)
    {
        $look->share();

        return response()->json([
            'message' => 'Look shared',
            'shares' => $look->shares,
            'share_url' => route('trending-looks.show', $look->id),
        ]);
    }
}

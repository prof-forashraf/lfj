<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Resources\VideoResource;
use App\Http\Resources\VideoCollection;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\VideoCollection
     */
    public function index(Request $request): VideoCollection
    {
        // Start with the base query for published videos
        $query = Video::query()->where('status', 'published');

        // Conditionally apply the 'featured' filter
        // Using when() is a clean way to add conditional clauses.
        $query->when($request->boolean('featured'), function ($query) {
            $query->where('is_featured', true);
        });

        // --- CORRECTED & IMPROVED SEARCH LOGIC ---
        // Conditionally apply the 'search' filter
        $query->when($request->input('search'), function ($query, $searchTerm) {
            // Group the 'where' clauses for the search to ensure 'OR' logic
            // doesn't conflict with the base 'where status=published'.
            // This generates SQL like: ... AND (title LIKE '...' OR description LIKE '...')
            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        });

        // Finally, order the results and paginate. This should be the last step.
        $videos = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 12));

        return new VideoCollection($videos);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \App\Http\Resources\VideoResource
     */
    public function show(Video $video): VideoResource
    {
        // This check is good practice to prevent direct access to non-published content
        if ($video->status !== 'published') {
            abort(404, 'Video not found or not published.');
        }

        return new VideoResource($video);
    }
}
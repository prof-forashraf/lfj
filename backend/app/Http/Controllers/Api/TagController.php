<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Resources\TagCollection;
// use App\Http\Resources\PostCollection; // If showing posts here

class TagController extends Controller
{
    public function index(Request $request): TagCollection
    {
        $tags = Tag::query()
            ->withCount('posts')
            ->orderBy('name')
            ->paginate($request->input('per_page', 20));
        return new TagCollection($tags);
    }

    public function show(Tag $tag): TagResource
    {
        $tag->loadCount('posts');
        return new TagResource($tag);
    }

}
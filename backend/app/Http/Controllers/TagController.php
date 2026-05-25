<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(Tag $tag) // Route model binding by slug
    {
        $tag->loadMissing([
            'posts' => function ($query) {
                $query->where('status', 'published')
                    ->orderBy('published_at', 'desc')
                    ->with(['author', 'categories', 'tags']);
            }
        ]);

        $posts = $tag->posts()->paginate(9);

        return view('tags.show', compact('tag', 'posts'));
    }
}
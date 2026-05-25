<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;

class SeoObserver
{
    /**
     * Handle the Post "saving" event.
     */
    public function saving(Post $post): void
    {
        if (!$post->getRawOriginal('reading_time') || $post->isDirty('content')) {
            $wordCount = Str::wordCount(strip_tags($post->content));
            $post->reading_time = max(1, (int) ceil($wordCount / 200));
        }
    }
}

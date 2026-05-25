<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrendingLook extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_url',
        'jewelry_items',
        'likes',
        'views',
        'shares',
        'status',
        'category',
    ];

    protected $casts = [
        'jewelry_items' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function like()
    {
        $this->increment('likes');
    }

    public function unlike()
    {
        $this->decrement('likes');
    }

    public function share()
    {
        $this->increment('shares');
    }
}

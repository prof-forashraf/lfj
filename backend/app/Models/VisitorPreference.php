<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorPreference extends Model
{
    use HasFactory;

    protected $table = 'visitor_preferences';

    protected $fillable = [
        'visitor_id',
        'user_id',
        'theme',
        'language',
        'layout',
        'favorite_categories',
        'favorite_tags',
        'recently_viewed_products',
        'recently_viewed_posts',
        'saved_preferences',
    ];

    protected $casts = [
        'favorite_categories' => 'array',
        'favorite_tags' => 'array',
        'recently_viewed_products' => 'array',
        'recently_viewed_posts' => 'array',
        'saved_preferences' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

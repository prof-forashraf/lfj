<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomJewelryDesign extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'jewelry_type',
        'materials',
        'customizations',
        'estimated_price',
        'is_public',
        'preview_image',
    ];

    protected $casts = [
        'materials' => 'array',
        'customizations' => 'array',
        'preview_image' => 'array',
        'estimated_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

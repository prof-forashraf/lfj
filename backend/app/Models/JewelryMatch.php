<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JewelryMatch extends Model
{
    protected $fillable = [
        'user_id',
        'gemstone_type',
        'jewelry_type',
        'difficulty_level',
        'score',
        'time_taken',
        'answer_details',
    ];

    protected $casts = [
        'answer_details' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

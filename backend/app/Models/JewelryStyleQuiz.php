<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JewelryStyleQuiz extends Model
{
    protected $fillable = [
        'user_id',
        'answers',
        'personality_type',
        'recommendations',
    ];

    protected $casts = [
        'answers' => 'array',
        'recommendations' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

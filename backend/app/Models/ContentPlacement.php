<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentPlacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'section_key',
        'block_type',
        'title',
        'description',
        'settings',
        'is_enabled',
        'starts_at',
        'ends_at',
        'sort_order',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_enabled' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_enabled', true)
            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn (Builder $q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }
}

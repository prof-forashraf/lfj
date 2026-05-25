<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'metal_type',
        'price',
        'currency',
        'rate_date',
        'updated_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rate_date' => 'date',
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

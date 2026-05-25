<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiamondPricing extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'diamond_pricings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'carat_range_min',
        'carat_range_max',
        'cut_grade',
        'color_grade',
        'clarity_grade',
        'base_price_per_carat',
        'price_multiplier',
        'shape',
        'fluorescence',
        'certification',
        'date_updated',
        'market_region',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'date_updated' => 'date',
        'carat_range_min' => 'float',
        'carat_range_max' => 'float',
        'base_price_per_carat' => 'float',
        'price_multiplier' => 'float',
    ];
}

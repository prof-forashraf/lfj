<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JewelryHoroscope extends Model
{
    protected $fillable = [
        'zodiac_sign',
        'date',
        'description',
        'recommended_jewelry',
        'lucky_color',
        'lucky_gemstone',
    ];

    protected $casts = [
        'date' => 'date',
        'recommended_jewelry' => 'array',
    ];
}

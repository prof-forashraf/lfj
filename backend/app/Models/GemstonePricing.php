<?php
// app/Models/GemstonePricing.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GemstonePricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price_per_carat',
        'is_active',
    ];

    protected $casts = [
        'price_per_carat' => 'float',
        'is_active' => 'boolean',
    ];
}

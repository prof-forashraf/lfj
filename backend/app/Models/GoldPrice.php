<?php
// app/Models/GoldPrice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * This is a security feature to prevent unintended data from being saved.
     * Only the fields listed here can be filled using methods like `create()` or `update()`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date_recorded',
        'timestamp_recorded',
        'currency_code',
        'price_per_ounce',
        'price_per_gram_24k',
        'price_per_gram_22k',
        'price_per_gram_18k',
        'price_per_gram_14k',
        'price_per_gram_10k',
        'market_open',
        'market_high',
        'market_low',
        'market_close',
        'volume',
        'source',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * This ensures that when you retrieve data, the 'date_recorded' field
     * is automatically converted to a Carbon date instance.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_recorded' => 'date',
        'is_active' => 'boolean',
    ];
}

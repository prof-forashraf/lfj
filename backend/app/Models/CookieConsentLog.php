<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookieConsentLog extends Model
{
    use HasFactory;

    protected $table = 'cookie_consent_logs';

    protected $fillable = [
        'cookie_consent_id',
        'action',
        'previous_state',
        'new_state',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'previous_state' => 'array',
        'new_state' => 'array',
    ];

    public function consent()
    {
        return $this->belongsTo(CookieConsent::class, 'cookie_consent_id');
    }
}

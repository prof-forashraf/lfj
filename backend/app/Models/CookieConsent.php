<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    use HasFactory;

    protected $table = 'cookie_consents';

    protected $fillable = [
        'visitor_id',
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'essential',
        'analytics',
        'marketing',
        'preferences',
        'meta',
    ];

    protected $casts = [
        'essential' => 'boolean',
        'analytics' => 'boolean',
        'marketing' => 'boolean',
        'preferences' => 'boolean',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(CookieConsentLog::class);
    }
}

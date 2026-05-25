<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CookieConsent;
use App\Models\CookieConsentLog;

class CookieConsentController extends Controller
{
    public function show($visitorId)
    {
        $consent = CookieConsent::where('visitor_id', $visitorId)->latest()->first();
        if (!$consent) {
            return response()->json(null, 204);
        }
        return response()->json($consent);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visitor_id' => 'sometimes|string|max:64',
            'session_id' => 'sometimes|string|max:128',
            'essential' => 'boolean',
            'analytics' => 'boolean',
            'marketing' => 'boolean',
            'preferences' => 'boolean',
            'meta' => 'sometimes|array',
        ]);

        $visitorId = $data['visitor_id'] ?? (string) Str::uuid();

        $existing = CookieConsent::where('visitor_id', $visitorId)->latest()->first();
        $previous = $existing ? $existing->toArray() : null;

        $consent = CookieConsent::updateOrCreate(
            ['visitor_id' => $visitorId],
            array_merge($data, [
                'visitor_id' => $visitorId,
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
        );

        CookieConsentLog::create([
            'cookie_consent_id' => $consent->id,
            'action' => $existing ? 'updated' : 'created',
            'previous_state' => $previous,
            'new_state' => $consent->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json($consent, $existing ? 200 : 201);
    }

    public function update(Request $request, $id)
    {
        $consent = CookieConsent::find($id);
        if (!$consent) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $data = $request->validate([
            'essential' => 'boolean',
            'analytics' => 'boolean',
            'marketing' => 'boolean',
            'preferences' => 'boolean',
            'meta' => 'sometimes|array',
        ]);

        $previous = $consent->toArray();
        $consent->update($data);

        CookieConsentLog::create([
            'cookie_consent_id' => $consent->id,
            'action' => 'updated',
            'previous_state' => $previous,
            'new_state' => $consent->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json($consent);
    }
}

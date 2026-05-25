<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\VisitorPreference;

class VisitorPreferenceController extends Controller
{
    public function show($visitorId)
    {
        $pref = VisitorPreference::where('visitor_id', $visitorId)->latest()->first();
        if (!$pref) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($pref);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visitor_id' => 'sometimes|string|max:64',
            'theme' => 'sometimes|string|max:32',
            'language' => 'sometimes|string|max:8',
            'layout' => 'sometimes|string|max:32',
            'favorite_categories' => 'sometimes|array',
            'favorite_tags' => 'sometimes|array',
            'recently_viewed_products' => 'sometimes|array',
            'recently_viewed_posts' => 'sometimes|array',
            'saved_preferences' => 'sometimes|array',
        ]);

        $visitorId = $data['visitor_id'] ?? (string) Str::uuid();

        $pref = VisitorPreference::updateOrCreate(
            ['visitor_id' => $visitorId],
            array_merge($data, [
                'visitor_id' => $visitorId,
                'user_id' => auth()->id(),
            ])
        );

        return response()->json($pref, 201);
    }

    public function update(Request $request, $id)
    {
        $pref = VisitorPreference::find($id);
        if (!$pref) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $data = $request->validate([
            'theme' => 'sometimes|string|max:32',
            'language' => 'sometimes|string|max:8',
            'layout' => 'sometimes|string|max:32',
            'favorite_categories' => 'sometimes|array',
            'favorite_tags' => 'sometimes|array',
            'recently_viewed_products' => 'sometimes|array',
            'recently_viewed_posts' => 'sometimes|array',
            'saved_preferences' => 'sometimes|array',
        ]);

        $pref->update($data);

        return response()->json($pref);
    }
}

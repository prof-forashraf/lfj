<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentPlacement;
use Illuminate\Http\Request;

class ContentPlacementController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'page_key' => ['nullable', 'string', 'max:100'],
        ]);

        $placements = ContentPlacement::query()
            ->active()
            ->when($request->input('page_key'), fn ($query, $pageKey) => $query->where('page_key', $pageKey))
            ->orderBy('sort_order')
            ->get();

        return response()->json(['data' => $placements]);
    }
}

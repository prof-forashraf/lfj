<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SeoService;

class SeoController extends Controller
{
    public function page(string $page, SeoService $seoService)
    {
        return response()->json($seoService->forPage($page));
    }
}

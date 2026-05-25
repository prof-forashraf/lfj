<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JewelryHoroscope;
use Illuminate\Http\Request;

class JewelryHoroscopeController extends Controller
{
    /**
     * Get today's horoscope for a zodiac sign.
     */
    public function show(Request $request, string $zodiacSign)
    {
        $date = $request->get('date', now()->toDateString());

        $horoscope = JewelryHoroscope::where('zodiac_sign', ucfirst($zodiacSign))
            ->where('date', $date)
            ->first();

        if (!$horoscope) {
            return response()->json(['message' => 'Horoscope not found'], 404);
        }

        return response()->json($horoscope);
    }

    /**
     * Get all horoscopes for today.
     */
    public function index(Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $horoscopes = JewelryHoroscope::where('date', $date)
            ->orderBy('zodiac_sign')
            ->get();

        return response()->json($horoscopes);
    }
}

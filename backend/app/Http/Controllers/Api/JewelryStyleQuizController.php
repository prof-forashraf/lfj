<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JewelryStyleQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JewelryStyleQuizController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        // Simple logic to determine personality type based on answers
        $personalityType = $this->determinePersonalityType($request->answers);

        // Get recommendations based on personality
        $recommendations = $this->getRecommendations($personalityType);

        $quiz = JewelryStyleQuiz::create([
            'user_id' => Auth::id(),
            'answers' => $request->answers,
            'personality_type' => $personalityType,
            'recommendations' => $recommendations,
        ]);

        return response()->json([
            'success' => true,
            'data' => $quiz,
        ]);
    }

    private function determinePersonalityType(array $answers): string
    {
        // Simple scoring logic - customize based on quiz questions
        $scores = ['Classic' => 0, 'Bohemian' => 0, 'Modern' => 0];

        foreach ($answers as $answer) {
            if (isset($answer['score'])) {
                foreach ($answer['score'] as $type => $points) {
                    $scores[$type] += $points;
                }
            }
        }

        return array_keys($scores, max($scores))[0];
    }

    private function getRecommendations(string $personality): array
    {
        // Return product IDs or slugs based on personality
        $recommendations = [
            'Classic' => ['classic-necklace-1', 'pearl-earrings'],
            'Bohemian' => ['boho-bracelet', 'layered-necklace'],
            'Modern' => ['minimalist-ring', 'geometric-earrings'],
        ];

        return $recommendations[$personality] ?? [];
    }
}

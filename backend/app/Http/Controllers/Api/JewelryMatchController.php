<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JewelryMatch;
use Illuminate\Http\Request;

class JewelryMatchController extends Controller
{
    /**
     * Get leaderboard for jewelry match game
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $difficulty = $request->get('difficulty', null);

        $query = JewelryMatch::select('user_id', \DB::raw('MAX(score) as best_score'), 
                                     \DB::raw('COUNT(*) as games_played'), 
                                     \DB::raw('AVG(score) as avg_score'))
            ->groupBy('user_id')
            ->orderByDesc('best_score')
            ->limit($limit);

        if ($difficulty) {
            $query->where('difficulty_level', $difficulty);
        }

        return response()->json($query->get());
    }

    /**
     * Submit a match game result
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gemstone_type' => 'required|string',
            'jewelry_type' => 'required|string',
            'difficulty_level' => 'required|integer|between:1,5',
            'score' => 'required|integer|min:0',
            'time_taken' => 'required|integer|min:0',
            'answer_details' => 'array',
        ]);

        $match = JewelryMatch::create([
            'user_id' => auth()->id(),
            ...$validated,
        ]);

        return response()->json([
            'message' => 'Score recorded successfully',
            'data' => $match,
            'rewards' => $this->calculateRewards($match),
        ]);
    }

    /**
     * Get user's match game statistics
     */
    public function show(Request $request)
    {
        $matches = JewelryMatch::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        $stats = [
            'total_games' => $matches->total(),
            'best_score' => JewelryMatch::where('user_id', auth()->id())->max('score'),
            'avg_score' => round(JewelryMatch::where('user_id', auth()->id())->avg('score')),
            'total_time' => JewelryMatch::where('user_id', auth()->id())->sum('time_taken'),
        ];

        return response()->json([
            'stats' => $stats,
            'recent_games' => $matches,
        ]);
    }

    /**
     * Calculate rewards based on performance
     */
    private function calculateRewards(JewelryMatch $match): array
    {
        $rewards = ['points' => 0, 'badges' => []];

        // Points calculation
        $rewards['points'] = intval($match->score * (1 + $match->difficulty_level * 0.2));

        // Badges
        if ($match->score >= 90) {
            $rewards['badges'][] = 'Gemstone Expert';
        }
        if ($match->difficulty_level >= 5 && $match->score >= 80) {
            $rewards['badges'][] = 'Master Matcher';
        }
        if ($match->time_taken < 30) {
            $rewards['badges'][] = 'Speed Demon';
        }

        return $rewards;
    }
}

<?php

namespace App\Filament\Resources\JewelryStyleQuizResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuizAnalyticsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalQuizzes = \App\Models\JewelryStyleQuiz::count();
        $todayQuizzes = \App\Models\JewelryStyleQuiz::whereDate('created_at', today())->count();
        $classicCount = \App\Models\JewelryStyleQuiz::where('personality_type', 'Classic')->count();

        return [
            Stat::make('Total Quizzes', $totalQuizzes)
                ->description('All time quiz completions')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Today\'s Quizzes', $todayQuizzes)
                ->description('Completed today')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
            Stat::make('Classic Style', $classicCount)
                ->description('Most popular personality type')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}

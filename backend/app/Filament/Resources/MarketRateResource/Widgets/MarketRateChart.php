<?php

namespace App\Filament\Resources\MarketRateResource\Widgets;

use App\Models\MarketRate;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class MarketRateChart extends ChartWidget
{
    protected static ?string $heading = 'Market Rate Trends';

    protected function getData(): array
    {
        $data = MarketRate::selectRaw('metal_type, rate_date, price')
            ->where('rate_date', '>=', now()->subDays(30))
            ->orderBy('rate_date')
            ->get()
            ->groupBy('metal_type');

        $datasets = [];
        $labels = collect();

        foreach ($data as $metal => $rates) {
            $labels = $labels->merge($rates->pluck('rate_date')->map->format('M d'));
            $datasets[] = [
                'label' => ucfirst($metal),
                'data' => $rates->pluck('price')->toArray(),
                'borderColor' => $this->getColorForMetal($metal),
                'backgroundColor' => $this->getColorForMetal($metal, 0.1),
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels->unique()->sort()->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getColorForMetal(string $metal): string
    {
        return match ($metal) {
            'gold' => '#FFD700',
            'silver' => '#C0C0C0',
            'platinum' => '#E5E4E2',
            'palladium' => '#FFD700', // similar to gold
            default => '#000000',
        };
    }
}

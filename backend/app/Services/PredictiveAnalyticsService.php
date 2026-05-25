<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class PredictiveAnalyticsService
{
    /**
     * Predict sales for future periods using historical data
     */
    public function predictSales(array $historicalData, int $forecastPeriods = 12): array
    {
        $model = $this->trainSalesModel($historicalData);

        $predictions = [];
        $lastDate = end($historicalData)['date'] ?? now()->toDateString();

        for ($i = 1; $i <= $forecastPeriods; $i++) {
            $futureDate = date('Y-m-d', strtotime($lastDate . " +{$i} months"));
            $prediction = $model->predict($i);

            $predictions[] = [
                'date' => $futureDate,
                'predicted_sales' => max(0, $prediction),
                'confidence_interval' => [
                    'lower' => max(0, $prediction * 0.8),
                    'upper' => $prediction * 1.2,
                ],
                'seasonal_factor' => $this->getSeasonalFactor($futureDate),
            ];
        }

        return [
            'predictions' => $predictions,
            'model_accuracy' => $this->calculateModelAccuracy($model, $historicalData),
            'seasonal_trends' => $this->analyzeSeasonality($historicalData),
            'price_elasticity' => $this->calculatePriceElasticity($historicalData),
            'key_insights' => $this->generateKeyInsights($predictions, $historicalData),
        ];
    }

    /**
     * Train a simple sales prediction model
     */
    private function trainSalesModel(array $historicalData): object
    {
        // Simple linear regression model for demonstration
        // In production, you'd use more sophisticated ML models

        $data = collect($historicalData);
        $n = $data->count();

        if ($n < 2) {
            return (object) ['predict' => fn($x) => 0];
        }

        // Calculate linear regression
        $sumX = $data->keys()->sum();
        $sumY = $data->sum('sales');
        $sumXY = $data->map(fn($item, $index) => $index * $item['sales'])->sum();
        $sumXX = $data->keys()->map(fn($x) => $x * $x)->sum();

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumXX - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;

        return (object) [
            'predict' => function($x) use ($slope, $intercept) {
                return $slope * $x + $intercept;
            },
            'slope' => $slope,
            'intercept' => $intercept,
        ];
    }

    /**
     * Calculate model accuracy
     */
    private function calculateModelAccuracy(object $model, array $historicalData): float
    {
        if (count($historicalData) < 3) {
            return 0.5; // Default accuracy
        }

        $errors = [];
        $data = collect($historicalData);

        foreach ($data as $index => $item) {
            if ($index < count($historicalData) - 1) { // Leave out last point for validation
                $predicted = $model->predict($index);
                $actual = $item['sales'];
                $errors[] = abs($predicted - $actual) / max($actual, 1); // Percentage error
            }
        }

        $meanAbsolutePercentageError = collect($errors)->avg();
        $accuracy = max(0, 1 - $meanAbsolutePercentageError);

        return min(1.0, $accuracy);
    }

    /**
     * Analyze seasonal patterns in sales data
     */
    public function analyzeSeasonality(array $historicalData): array
    {
        $data = collect($historicalData);

        // Group by month to identify seasonal patterns
        $monthlySales = $data->groupBy(function($item) {
            return date('m', strtotime($item['date']));
        })->map(function($monthData) {
            return [
                'month' => $monthData->first()['date'],
                'average_sales' => $monthData->avg('sales'),
                'total_sales' => $monthData->sum('sales'),
                'count' => $monthData->count(),
            ];
        });

        $overallAverage = $data->avg('sales');

        $seasonalFactors = $monthlySales->map(function($month) use ($overallAverage) {
            return [
                'month' => date('F', strtotime($month['month'])),
                'factor' => $overallAverage > 0 ? $month['average_sales'] / $overallAverage : 1,
                'performance' => $month['factor'] > 1.1 ? 'high' : ($month['factor'] < 0.9 ? 'low' : 'normal'),
            ];
        });

        return [
            'seasonal_factors' => $seasonalFactors->values()->toArray(),
            'peak_months' => $seasonalFactors->where('factor', '>', 1.1)->pluck('month')->toArray(),
            'slow_months' => $seasonalFactors->where('factor', '<', 0.9)->pluck('month')->toArray(),
            'seasonality_strength' => $this->calculateSeasonalityStrength($seasonalFactors),
        ];
    }

    /**
     * Calculate price elasticity from sales data
     */
    private function calculatePriceElasticity(array $historicalData): float
    {
        // In a real implementation, this would correlate price changes with sales changes
        // For now, return a simulated elasticity value
        return -0.8; // -0.8 means 1% price increase leads to 0.8% sales decrease
    }

    /**
     * Generate key insights from predictions and historical data
     */
    private function generateKeyInsights(array $predictions, array $historicalData): array
    {
        $insights = [];
        $historicalSales = collect($historicalData)->pluck('sales');
        $predictedSales = collect($predictions)->pluck('predicted_sales');

        // Trend analysis
        $historicalTrend = $this->calculateTrend($historicalSales);
        $predictedTrend = $this->calculateTrend($predictedSales);

        if ($predictedTrend > 0.1) {
            $insights[] = [
                'type' => 'growth',
                'message' => 'Strong growth predicted for the next ' . count($predictions) . ' months',
                'confidence' => 'high',
            ];
        } elseif ($predictedTrend < -0.1) {
            $insights[] = [
                'type' => 'decline',
                'message' => 'Potential sales decline expected - consider promotional activities',
                'confidence' => 'medium',
            ];
        }

        // Seasonal insights
        $seasonality = $this->analyzeSeasonality($historicalData);
        if (!empty($seasonality['peak_months'])) {
            $insights[] = [
                'type' => 'seasonal',
                'message' => 'Peak sales expected in: ' . implode(', ', $seasonality['peak_months']),
                'confidence' => 'high',
            ];
        }

        // Volatility analysis
        $volatility = $this->calculateVolatility($historicalSales);
        if ($volatility > 0.3) {
            $insights[] = [
                'type' => 'volatility',
                'message' => 'High sales volatility detected - consider inventory buffer',
                'confidence' => 'medium',
            ];
        }

        return $insights;
    }

    /**
     * Get seasonal factor for a specific date
     */
    private function getSeasonalFactor(string $date): float
    {
        $month = (int) date('m', strtotime($date));

        // Jewelry sales seasonal factors (based on typical patterns)
        $seasonalFactors = [
            1 => 0.85,   // January - post-holiday slump
            2 => 1.15,   // February - Valentine's Day
            3 => 0.90,   // March
            4 => 0.85,   // April
            5 => 1.10,   // May - Mother's Day
            6 => 1.20,   // June - Wedding season
            7 => 0.95,   // July
            8 => 0.90,   // August
            9 => 0.95,   // September
            10 => 1.05,  // October
            11 => 1.35,  // November - Holiday season
            12 => 1.45,  // December - Holiday peak
        ];

        return $seasonalFactors[$month] ?? 1.0;
    }

    /**
     * Calculate seasonality strength
     */
    private function calculateSeasonalityStrength(Collection $seasonalFactors): float
    {
        $factors = $seasonalFactors->pluck('factor');
        $mean = $factors->avg();
        $variance = $factors->map(fn($f) => pow($f - $mean, 2))->avg();

        return sqrt($variance) / $mean; // Coefficient of variation
    }

    /**
     * Calculate trend direction
     */
    private function calculateTrend(Collection $data): float
    {
        if ($data->count() < 2) return 0;

        $n = $data->count();
        $x = collect(range(0, $n - 1));
        $y = $data;

        $sumX = $x->sum();
        $sumY = $y->sum();
        $sumXY = $x->zip($y)->map(fn($pair) => $pair[0] * $pair[1])->sum();
        $sumXX = $x->map(fn($val) => $val * $val)->sum();

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumXX - $sumX * $sumX);

        // Normalize by average value
        $avgY = $y->avg();
        return $avgY > 0 ? $slope / $avgY : 0;
    }

    /**
     * Calculate sales volatility
     */
    private function calculateVolatility(Collection $data): float
    {
        if ($data->count() < 2) return 0;

        $mean = $data->avg();
        $variance = $data->map(fn($val) => pow($val - $mean, 2))->avg();

        return $mean > 0 ? sqrt($variance) / $mean : 0; // Coefficient of variation
    }

    /**
     * Predict customer lifetime value
     */
    public function predictCustomerLifetimeValue(User $user): array
    {
        // In a real implementation, this would use customer purchase history
        // For now, return simulated CLV
        return [
            'current_clv' => 0,
            'predicted_clv' => 1250.00,
            'confidence' => 0.75,
            'factors' => [
                'purchase_frequency' => 'monthly',
                'average_order_value' => 85.00,
                'customer_tenure' => '2_years',
                'engagement_score' => 8.5,
            ],
        ];
    }

    /**
     * Analyze product performance trends
     */
    public function analyzeProductTrends(): array
    {
        // Get product performance data from analytics
        $productTrends = DB::table('product_analytics')
            ->select('product_id', DB::raw('COUNT(*) as views'), DB::raw('DATE(created_at) as date'))
            ->where('created_at', '>=', now()->subDays(90))
            ->groupBy('product_id', 'date')
            ->orderBy('date')
            ->get()
            ->groupBy('product_id');

        $trends = [];
        foreach ($productTrends as $productId => $data) {
            $views = $data->pluck('views', 'date');

            $trends[$productId] = [
                'product_id' => $productId,
                'total_views' => $views->sum(),
                'trend' => $this->calculateTrend($views->values()),
                'volatility' => $this->calculateVolatility($views->values()),
                'peak_date' => $views->sortDesc()->keys()->first(),
                'consistency_score' => $this->calculateConsistencyScore($views->values()),
            ];
        }

        return [
            'trends' => $trends,
            'top_performing' => collect($trends)->sortByDesc('total_views')->take(10)->toArray(),
            'rising_stars' => collect($trends)->where('trend', '>', 0.1)->sortByDesc('trend')->take(5)->toArray(),
            'declining_products' => collect($trends)->where('trend', '<', -0.1)->sortBy('trend')->take(5)->toArray(),
        ];
    }

    /**
     * Calculate consistency score for product views
     */
    private function calculateConsistencyScore(Collection $data): float
    {
        if ($data->count() < 7) return 0;

        $mean = $data->avg();
        $deviations = $data->map(fn($val) => abs($val - $mean));

        // Lower deviation means higher consistency
        $avgDeviation = $deviations->avg();
        $consistency = $mean > 0 ? 1 - ($avgDeviation / $mean) : 0;

        return max(0, min(1, $consistency));
    }

    /**
     * Forecast inventory needs
     */
    public function forecastInventoryNeeds(): array
    {
        $salesPredictions = $this->predictSales($this->getHistoricalSalesData(), 6);

        $inventoryForecast = [];
        foreach ($salesPredictions['predictions'] as $prediction) {
            $inventoryForecast[] = [
                'date' => $prediction['date'],
                'predicted_sales' => $prediction['predicted_sales'],
                'recommended_inventory' => $prediction['predicted_sales'] * 1.2, // 20% buffer
                'safety_stock' => $prediction['predicted_sales'] * 0.3, // 30% safety stock
            ];
        }

        return [
            'forecast' => $inventoryForecast,
            'total_recommended_inventory' => collect($inventoryForecast)->sum('recommended_inventory'),
            'insights' => [
                'stock_out_risk' => $this->assessStockOutRisk($inventoryForecast),
                'overstock_risk' => $this->assessOverstockRisk($inventoryForecast),
            ],
        ];
    }

    /**
     * Get historical sales data (placeholder)
     */
    private function getHistoricalSalesData(): array
    {
        // In a real implementation, this would query actual sales data
        // For now, return simulated data
        $data = [];
        for ($i = 12; $i >= 1; $i--) {
            $date = now()->subMonths($i)->format('Y-m-d');
            $baseSales = 1000;
            $seasonalFactor = $this->getSeasonalFactor($date);
            $trend = 1 + ($i * 0.02); // Slight upward trend

            $data[] = [
                'date' => $date,
                'sales' => intval($baseSales * $seasonalFactor * $trend * (0.8 + rand(0, 40) / 100)),
            ];
        }

        return $data;
    }

    /**
     * Assess stock-out risk
     */
    private function assessStockOutRisk(array $forecast): string
    {
        $highRiskPeriods = collect($forecast)->where('predicted_sales', '>', 1500)->count();
        $totalPeriods = count($forecast);

        $riskRatio = $highRiskPeriods / $totalPeriods;

        if ($riskRatio > 0.5) return 'high';
        if ($riskRatio > 0.25) return 'medium';
        return 'low';
    }

    /**
     * Assess overstock risk
     */
    private function assessOverstockRisk(array $forecast): string
    {
        $lowSalesPeriods = collect($forecast)->where('predicted_sales', '<', 800)->count();
        $totalPeriods = count($forecast);

        $riskRatio = $lowSalesPeriods / $totalPeriods;

        if ($riskRatio > 0.5) return 'high';
        if ($riskRatio > 0.25) return 'medium';
        return 'low';
    }

    /**
     * Generate comprehensive business forecast
     */
    public function generateBusinessForecast(int $months = 12): array
    {
        $salesForecast = $this->predictSales($this->getHistoricalSalesData(), $months);
        $inventoryForecast = $this->forecastInventoryNeeds();
        $productTrends = $this->analyzeProductTrends();

        return [
            'sales_forecast' => $salesForecast,
            'inventory_forecast' => $inventoryForecast,
            'product_trends' => $productTrends,
            'financial_projections' => $this->calculateFinancialProjections($salesForecast),
            'recommendations' => $this->generateBusinessRecommendations($salesForecast, $inventoryForecast, $productTrends),
            'generated_at' => now(),
        ];
    }

    /**
     * Calculate financial projections
     */
    private function calculateFinancialProjections(array $salesForecast): array
    {
        $projections = [];

        foreach ($salesForecast['predictions'] as $prediction) {
            $revenue = $prediction['predicted_sales'] * 85; // Average order value
            $cogs = $revenue * 0.4; // 40% cost of goods
            $grossProfit = $revenue - $cogs;
            $operatingExpenses = $revenue * 0.25; // 25% operating expenses
            $netProfit = $grossProfit - $operatingExpenses;

            $projections[] = [
                'date' => $prediction['date'],
                'revenue' => $revenue,
                'gross_profit' => $grossProfit,
                'net_profit' => $netProfit,
                'profit_margin' => $revenue > 0 ? ($netProfit / $revenue) * 100 : 0,
            ];
        }

        return [
            'monthly_projections' => $projections,
            'annual_totals' => [
                'total_revenue' => collect($projections)->sum('revenue'),
                'total_net_profit' => collect($projections)->sum('net_profit'),
                'average_margin' => collect($projections)->avg('profit_margin'),
            ],
        ];
    }

    /**
     * Generate business recommendations
     */
    private function generateBusinessRecommendations(array $salesForecast, array $inventoryForecast, array $productTrends): array
    {
        $recommendations = [];

        // Sales trend recommendations
        $avgPredictedSales = collect($salesForecast['predictions'])->avg('predicted_sales');
        $lastHistoricalSales = end($this->getHistoricalSalesData())['sales'] ?? 0;

        if ($avgPredictedSales > $lastHistoricalSales * 1.1) {
            $recommendations[] = [
                'category' => 'sales',
                'priority' => 'high',
                'message' => 'Strong sales growth predicted - consider expanding inventory and marketing budget',
            ];
        }

        // Inventory recommendations
        if ($inventoryForecast['insights']['stock_out_risk'] === 'high') {
            $recommendations[] = [
                'category' => 'inventory',
                'priority' => 'high',
                'message' => 'High risk of stock-outs - increase safety stock levels',
            ];
        }

        // Product recommendations
        $risingStars = $productTrends['rising_stars'] ?? [];
        if (!empty($risingStars)) {
            $recommendations[] = [
                'category' => 'products',
                'priority' => 'medium',
                'message' => 'Promote rising star products: ' . implode(', ', array_keys($risingStars)),
            ];
        }

        return $recommendations;
    }
}
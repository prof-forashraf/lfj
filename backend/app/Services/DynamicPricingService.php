<?php

namespace App\Services;

use App\Models\AffiliateProduct;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DynamicPricingService
{
    /**
     * Calculate optimal price for a product using AI-driven analysis
     */
    public function calculateOptimalPrice(AffiliateProduct $product): array
    {
        // Get market data and competition analysis
        $marketData = $this->getMarketData($product);
        $competition = $this->analyzeCompetition($product);
        $demand = $this->predictDemand($product);
        $inventory = $this->analyzeInventory($product);

        // Calculate optimal price using multiple factors
        $basePrice = $product->price;
        $optimalPrice = $this->optimizePrice($basePrice, $marketData, $competition, $demand, $inventory);

        // Calculate price elasticity
        $elasticity = $this->calculatePriceElasticity($product);

        // Generate pricing recommendations
        $recommendations = $this->generatePricingRecommendations($optimalPrice, $basePrice, $elasticity);

        return [
            'current_price' => $basePrice,
            'optimal_price' => $optimalPrice,
            'price_range' => [
                'min' => $optimalPrice * 0.85,
                'max' => $optimalPrice * 1.15,
            ],
            'confidence_score' => $this->calculateConfidenceScore($marketData, $competition),
            'elasticity' => $elasticity,
            'recommendations' => $recommendations,
            'market_factors' => [
                'competition_level' => $competition['level'],
                'demand_trend' => $demand['trend'],
                'seasonal_adjustment' => $this->getSeasonalAdjustment(),
                'market_sentiment' => $this->getMarketSentiment(),
            ],
            'last_updated' => now(),
        ];
    }

    /**
     * Get market data for the product category
     */
    private function getMarketData(AffiliateProduct $product): array
    {
        $cacheKey = "market_data_{$product->category_id}";

        return Cache::remember($cacheKey, 3600, function () use ($product) {
            // In a real implementation, this would fetch from market APIs
            // For now, return simulated data
            return [
                'average_price' => $product->price * 1.1,
                'price_range' => [
                    'min' => $product->price * 0.8,
                    'max' => $product->price * 1.4,
                ],
                'market_trend' => 'stable', // stable, increasing, decreasing
                'volatility' => 0.15, // 15% volatility
                'sample_size' => 150,
            ];
        });
    }

    /**
     * Analyze competition for the product
     */
    private function analyzeCompetition(AffiliateProduct $product): array
    {
        // Find similar products in the same category
        $similarProducts = AffiliateProduct::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->get();

        $avgCompetitorPrice = $similarProducts->avg('price');
        $priceDifference = $product->price - $avgCompetitorPrice;
        $competitionLevel = $this->calculateCompetitionLevel($similarProducts->count());

        return [
            'competitor_count' => $similarProducts->count(),
            'average_competitor_price' => $avgCompetitorPrice,
            'price_position' => $priceDifference > 0 ? 'premium' : 'budget',
            'price_difference_percent' => $avgCompetitorPrice > 0 ? ($priceDifference / $avgCompetitorPrice) * 100 : 0,
            'level' => $competitionLevel, // low, medium, high
        ];
    }

    /**
     * Predict demand for the product
     */
    private function predictDemand(AffiliateProduct $product): array
    {
        // Simple demand prediction based on product attributes
        $baseDemand = 100; // Base demand score

        // Adjust based on product features
        $demandMultiplier = 1.0;

        if ($product->is_featured) {
            $demandMultiplier *= 1.3; // Featured products have 30% higher demand
        }

        if ($product->price < 100) {
            $demandMultiplier *= 1.2; // Lower price products have higher demand
        }

        // Seasonal adjustment
        $seasonalMultiplier = $this->getSeasonalDemandMultiplier();

        $predictedDemand = $baseDemand * $demandMultiplier * $seasonalMultiplier;

        return [
            'predicted_demand' => $predictedDemand,
            'trend' => $this->determineDemandTrend($predictedDemand, $baseDemand),
            'seasonal_factor' => $seasonalMultiplier,
            'confidence' => 0.75, // 75% confidence in prediction
        ];
    }

    /**
     * Analyze inventory levels
     */
    private function analyzeInventory(AffiliateProduct $product): array
    {
        // In a real implementation, this would check actual inventory levels
        // For now, simulate inventory analysis
        return [
            'stock_level' => 'normal', // low, normal, high
            'days_of_supply' => 30,
            'reorder_point' => 10,
            'inventory_turnover' => 12, // Times per year
        ];
    }

    /**
     * Optimize price using multiple factors
     */
    private function optimizePrice(float $basePrice, array $marketData, array $competition, array $demand, array $inventory): float
    {
        $optimalPrice = $basePrice;

        // Market-based adjustment
        $marketAdjustment = ($marketData['average_price'] - $basePrice) * 0.3; // 30% weight
        $optimalPrice += $marketAdjustment;

        // Competition-based adjustment
        $competitionAdjustment = 0;
        if ($competition['level'] === 'high') {
            // In high competition, price slightly below average
            $competitionAdjustment = ($competition['average_competitor_price'] - $optimalPrice) * 0.2;
        }
        $optimalPrice += $competitionAdjustment;

        // Demand-based adjustment
        $demandAdjustment = 0;
        if ($demand['trend'] === 'increasing') {
            $demandAdjustment = $optimalPrice * 0.05; // 5% increase for high demand
        } elseif ($demand['trend'] === 'decreasing') {
            $demandAdjustment = $optimalPrice * -0.03; // 3% decrease for low demand
        }
        $optimalPrice += $demandAdjustment;

        // Inventory-based adjustment
        $inventoryAdjustment = 0;
        if ($inventory['stock_level'] === 'high') {
            $inventoryAdjustment = $optimalPrice * -0.02; // Reduce price to move inventory
        }
        $optimalPrice += $inventoryAdjustment;

        // Ensure price stays within reasonable bounds
        $minPrice = $basePrice * 0.7; // Don't go below 70% of base price
        $maxPrice = $basePrice * 1.5; // Don't go above 150% of base price

        return max($minPrice, min($maxPrice, $optimalPrice));
    }

    /**
     * Calculate price elasticity
     */
    private function calculatePriceElasticity(AffiliateProduct $product): float
    {
        // Simplified price elasticity calculation
        // In luxury goods like jewelry, elasticity is typically low (inelastic)
        $baseElasticity = -0.5; // -0.5 means 1% price increase leads to 0.5% demand decrease

        // Adjust based on product characteristics
        if ($product->price < 100) {
            $baseElasticity *= 1.2; // More elastic for lower-priced items
        }

        if ($product->is_featured) {
            $baseElasticity *= 0.9; // Less elastic for featured products
        }

        return $baseElasticity;
    }

    /**
     * Generate pricing recommendations
     */
    private function generatePricingRecommendations(float $optimalPrice, float $currentPrice, float $elasticity): array
    {
        $recommendations = [];
        $priceDifference = $optimalPrice - $currentPrice;
        $differencePercent = abs($priceDifference / $currentPrice) * 100;

        if ($differencePercent > 10) {
            $direction = $priceDifference > 0 ? 'increase' : 'decrease';
            $recommendations[] = [
                'type' => 'price_adjustment',
                'action' => "Consider {$direction} price by " . number_format($differencePercent, 1) . "%",
                'impact' => $direction === 'increase' ? 'Higher margins' : 'Increased sales volume',
                'confidence' => 'medium',
            ];
        }

        // Elasticity-based recommendations
        if (abs($elasticity) > 1.0) {
            $recommendations[] = [
                'type' => 'elasticity_insight',
                'action' => 'Product is price elastic - small price changes significantly affect demand',
                'impact' => 'Careful with price adjustments',
                'confidence' => 'high',
            ];
        }

        // Seasonal recommendations
        $seasonalAdjustment = $this->getSeasonalAdjustment();
        if (abs($seasonalAdjustment) > 0.1) {
            $direction = $seasonalAdjustment > 0 ? 'increase' : 'decrease';
            $recommendations[] = [
                'type' => 'seasonal_pricing',
                'action' => "Consider seasonal {$direction} of " . number_format(abs($seasonalAdjustment) * 100, 1) . "%",
                'impact' => 'Optimize for seasonal demand',
                'confidence' => 'medium',
            ];
        }

        return $recommendations;
    }

    /**
     * Calculate confidence score for pricing recommendation
     */
    private function calculateConfidenceScore(array $marketData, array $competition): float
    {
        $confidence = 0.5; // Base confidence

        // Higher confidence with more market data
        if ($marketData['sample_size'] > 100) {
            $confidence += 0.2;
        }

        // Higher confidence with more competition data
        if ($competition['competitor_count'] > 10) {
            $confidence += 0.2;
        }

        // Lower confidence with high market volatility
        if ($marketData['volatility'] > 0.2) {
            $confidence -= 0.1;
        }

        return min(1.0, max(0.1, $confidence));
    }

    /**
     * Helper methods
     */
    private function calculateCompetitionLevel(int $competitorCount): string
    {
        if ($competitorCount < 5) return 'low';
        if ($competitorCount < 15) return 'medium';
        return 'high';
    }

    private function determineDemandTrend(float $predictedDemand, float $baseDemand): string
    {
        $ratio = $predictedDemand / $baseDemand;
        if ($ratio > 1.2) return 'increasing';
        if ($ratio < 0.8) return 'decreasing';
        return 'stable';
    }

    private function getSeasonalDemandMultiplier(): float
    {
        $month = now()->month;

        // Jewelry demand peaks during holidays and special occasions
        $seasonalMultipliers = [
            1 => 0.9,   // January - post-holiday slump
            2 => 1.0,   // February - Valentine's Day
            3 => 0.95,  // March
            4 => 0.9,   // April
            5 => 1.0,   // May - Mother's Day
            6 => 1.1,   // June - Wedding season
            7 => 0.95,  // July
            8 => 0.9,   // August
            9 => 0.95,  // September
            10 => 1.0,  // October
            11 => 1.3,  // November - Holiday season
            12 => 1.4,  // December - Holiday peak
        ];

        return $seasonalMultipliers[$month] ?? 1.0;
    }

    private function getSeasonalAdjustment(): float
    {
        $multiplier = $this->getSeasonalDemandMultiplier();
        return ($multiplier - 1.0) * 0.1; // Convert to price adjustment (10% of seasonal effect)
    }

    private function getMarketSentiment(): string
    {
        // In a real implementation, this would analyze market news, social media, etc.
        // For now, return a simulated sentiment
        $sentiments = ['bullish', 'bearish', 'neutral'];
        return $sentiments[array_rand($sentiments)];
    }

    /**
     * Get pricing insights for bulk operations
     */
    public function getPricingInsights(array $productIds): array
    {
        $insights = [];

        foreach ($productIds as $productId) {
            $product = AffiliateProduct::find($productId);
            if ($product) {
                $insights[$productId] = $this->calculateOptimalPrice($product);
            }
        }

        return $insights;
    }

    /**
     * Analyze pricing strategy effectiveness
     */
    public function analyzePricingStrategy(): array
    {
        $products = AffiliateProduct::where('status', 'active')->get();

        $totalProducts = $products->count();
        $avgPrice = $products->avg('price');
        $priceVariance = $products->map(fn($p) => pow($p->price - $avgPrice, 2))->avg();

        $priceRanges = [
            'budget' => $products->where('price', '<', 50)->count(),
            'mid_range' => $products->whereBetween('price', [50, 200])->count(),
            'premium' => $products->whereBetween('price', [200, 1000])->count(),
            'luxury' => $products->where('price', '>', 1000)->count(),
        ];

        return [
            'total_products' => $totalProducts,
            'average_price' => $avgPrice,
            'price_variance' => sqrt($priceVariance),
            'price_distribution' => $priceRanges,
            'recommended_strategy' => $this->recommendPricingStrategy($priceRanges, $avgPrice),
        ];
    }

    private function recommendPricingStrategy(array $priceRanges, float $avgPrice): string
    {
        $total = array_sum($priceRanges);

        if ($priceRanges['luxury'] / $total > 0.3) {
            return 'Premium positioning - focus on luxury market';
        } elseif ($priceRanges['budget'] / $total > 0.4) {
            return 'Value-driven strategy - compete on price';
        } else {
            return 'Balanced approach - mix of price points';
        }
    }
}
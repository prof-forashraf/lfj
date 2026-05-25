<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

// --- CORRECTED & CLEANED CONTROLLER IMPORTS ---
// All API controllers are now consistently imported from the `Api` namespace.
use App\Http\Controllers\Api\AffiliateProductController;
use App\Http\Controllers\Api\AdvancedSearchController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactFormController;
use App\Http\Controllers\Api\ContentPlacementController;
use App\Http\Controllers\Api\DiamondPriceController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\GoldPriceController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\MaterialPriceController;
use App\Http\Controllers\Api\PricingController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SeoController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\MonitoringController;
use App\Http\Controllers\Api\CustomJewelryDesignController;
use App\Http\Controllers\Api\JewelryHoroscopeController;
use App\Http\Controllers\Api\JewelryMatchController;
use App\Http\Controllers\Api\JewelryStyleQuizController;
use App\Http\Controllers\Api\TrendingLookController;
use App\Http\Controllers\Api\CookieConsentController;
use App\Http\Controllers\Api\VisitorPreferenceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// --- TEST ROUTE ---
Route::get('/test', function () {
    return response()->json(['message' => 'API is working', 'timestamp' => now()]);
});

Route::get('/posts-test', function () {
    $posts = App\Models\Post::where('status', 'published')->take(5)->get(['id', 'title', 'slug']);
    return response()->json($posts);
});

// ✅ RATE LIMITED: 5 login attempts per minute per IP
Route::middleware(['web', 'throttle:5,1'])->post('/login', [AuthController::class, 'login'])->name('api.login');
Route::middleware(['web', 'throttle:5,1'])->post('/register', [AuthController::class, 'register'])->name('api.register');
Route::middleware(['web', 'throttle:5,1'])->post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('api.forgot-password');

Route::get('/user', [AuthController::class, 'user'])->name('api.user');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::post('/jewelry-quiz', [JewelryStyleQuizController::class, 'store'])->name('api.jewelry-quiz.store');
    Route::apiResource('custom-jewelry-designs', CustomJewelryDesignController::class);
    Route::get('/horoscopes', [JewelryHoroscopeController::class, 'index'])->name('api.horoscopes.index');
    Route::get('/horoscopes/{zodiacSign}', [JewelryHoroscopeController::class, 'show'])->name('api.horoscopes.show');
    Route::get('/jewelry-matches/leaderboard', [JewelryMatchController::class, 'index'])->name('api.jewelry-matches.leaderboard');
    Route::post('/jewelry-matches', [JewelryMatchController::class, 'store'])->name('api.jewelry-matches.store');
    Route::get('/jewelry-matches/my-stats', [JewelryMatchController::class, 'show'])->name('api.jewelry-matches.stats');
    Route::get('/trending-looks', [TrendingLookController::class, 'index'])->name('api.trending-looks.index');
    Route::post('/trending-looks', [TrendingLookController::class, 'store'])->name('api.trending-looks.store');
    Route::get('/trending-looks/{look}', [TrendingLookController::class, 'show'])->name('api.trending-looks.show');
    Route::put('/trending-looks/{look}', [TrendingLookController::class, 'update'])->name('api.trending-looks.update');
    Route::delete('/trending-looks/{look}', [TrendingLookController::class, 'destroy'])->name('api.trending-looks.destroy');
    Route::post('/trending-looks/{look}/like', [TrendingLookController::class, 'like'])->name('api.trending-looks.like');
    Route::post('/trending-looks/{look}/unlike', [TrendingLookController::class, 'unlike'])->name('api.trending-looks.unlike');
    Route::post('/trending-looks/{look}/share', [TrendingLookController::class, 'share'])->name('api.trending-looks.share');
});

// --- BLOG & CONTENT ---
// Using apiResource for standard index/show routes is a Laravel best practice.
Route::apiResource('posts', PostController::class)->only(['index', 'show'])->parameters(['posts' => 'post:slug']);
Route::get('/categories/{category:slug}/products', [CategoryController::class, 'products'])->name('categories.products');
Route::apiResource('categories', CategoryController::class)->only(['index', 'show'])->parameters(['categories' => 'category:slug']);
Route::apiResource('tags', TagController::class)->only(['index', 'show'])->parameters(['tags' => 'tag:slug']);
Route::apiResource('events', EventController::class)->only(['index', 'show'])->parameters(['events' => 'event:slug']);
Route::apiResource('videos', VideoController::class)->only(['index', 'show']);
Route::get('/placements', [ContentPlacementController::class, 'index'])->name('api.placements.index');
Route::get('/seo/page/{page}', [SeoController::class, 'page'])->name('api.seo.page');
Route::get('/sitemap.xml', App\Http\Controllers\SitemapController::class)->name('api.sitemap');

// --- SHOP & PRODUCTS ---
// ✅ RATE LIMITED: 60 product queries per minute per IP
Route::middleware('throttle:60,1')->prefix('products')->name('api.products.')->group(function () {
    // This now becomes the main index route for products.
    Route::get('/', [AffiliateProductController::class, 'index'])->name('index');
    // ✅ RATE LIMITED: 20 search queries per minute per IP
    Route::middleware('throttle:20,1')->get('/search', [AffiliateProductController::class, 'search'])->name('search');
    Route::get('/featured', [AffiliateProductController::class, 'featured'])->name('featured');
    Route::get('/random', [AffiliateProductController::class, 'random'])->name('random');
    Route::get('/studio', [AffiliateProductController::class, 'getStudioProducts'])->name('studio');
    Route::post('/{product:amazon_asin}/click', [AffiliateProductController::class, 'trackClick'])->name('track-click');
    // Show a single product by its ASIN. Must be the last route in the group.
    Route::get('/{product:amazon_asin}', [AffiliateProductController::class, 'showByAsin'])->name('show');
});

// Advanced Search API with AI-powered features
Route::middleware('throttle:30,1')->prefix('search')->name('api.search.')->group(function () {
    Route::get('/advanced', [AdvancedSearchController::class, 'search'])->name('advanced');
    Route::get('/autocomplete', [AdvancedSearchController::class, 'autocomplete'])->name('autocomplete');
});

// ✅ RATE LIMITED: 120 tool requests per minute per IP
Route::middleware('throttle:120,1')->prefix('tools')->name('api.tools.')->group(function () {
    Route::get('/gold-price/{currency}', [MaterialPriceController::class, 'getGoldPrice'])->name('gold-price');
    Route::get('/silver-price/{currency}', [MaterialPriceController::class, 'getSilverPrice'])->name('silver-price');
    Route::get('/gold-historical/{currency}/{range?}', [GoldPriceController::class, 'getHistoricalPrices'])->name('gold-historical');
    Route::get('/diamond-price', [DiamondPriceController::class, 'getPrice'])->name('diamond-price');
    Route::get('/materials/metals', [MaterialController::class, 'getMetals'])->name('materials.metals');
    Route::get('/materials/gemstones', [MaterialController::class, 'getGemstones'])->name('materials.gemstones');
});

Route::middleware('throttle:120,1')->prefix('pricing')->name('api.pricing.')->group(function () {
    Route::post('/jewelry/quote', [PricingController::class, 'quote'])->name('jewelry.quote');
    Route::post('/resale/calculate', [PricingController::class, 'resale'])->name('resale.calculate');
    Route::post('/zakat', [PricingController::class, 'zakat'])->name('zakat.calculate');
    Route::get('/metal/{symbol}/{currency}', [PricingController::class, 'metalPrice'])->name('metal.price');
});

// --- UTILITIES ---
// ✅ RATE LIMITED: 30 contact form submissions per hour per IP
Route::middleware('throttle:30,60')->post('/contact-submissions', [ContactFormController::class, 'store'])->name('api.contact.store');

// --- COOKIE CONSENT & VISITOR PREFERENCES ---
Route::middleware('throttle:60,1')->post('/cookie-consents', [CookieConsentController::class, 'store'])->name('api.cookie-consents.store');
Route::get('/cookie-consents/{visitorId}', [CookieConsentController::class, 'show'])->name('api.cookie-consents.show');
Route::put('/cookie-consents/{id}', [CookieConsentController::class, 'update'])->name('api.cookie-consents.update');

Route::post('/visitor-preferences', [VisitorPreferenceController::class, 'store'])->name('api.visitor-preferences.store');
Route::get('/visitor-preferences/{visitorId}', [VisitorPreferenceController::class, 'show'])->name('api.visitor-preferences.show');
Route::put('/visitor-preferences/{id}', [VisitorPreferenceController::class, 'update'])->name('api.visitor-preferences.update');

// Enhanced health check endpoint with comprehensive monitoring
Route::get('/health', function () {
    try {
        $analyticsService = app(\App\Services\AnalyticsService::class);
        $systemHealth = $analyticsService->getSystemHealth();

        // Additional business metrics
        $businessMetrics = $analyticsService->getDashboardMetrics();

        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
            'services' => [
                'database' => $systemHealth['database'] ?? false,
                'cache' => $systemHealth['cache'] ?? false,
                'memory_usage' => $systemHealth['memory_usage'] ?? null,
                'uptime' => $systemHealth['uptime'] ?? 'N/A',
            ],
            'system_health' => $systemHealth,
            'business_metrics' => [
                'total_products' => $businessMetrics['total_products'] ?? 0,
                'total_users' => $businessMetrics['total_users'] ?? 0,
                'total_categories' => $businessMetrics['total_categories'] ?? 0,
            ],
            'metal_sync' => $analyticsService->getMetalSyncHealth(),
            'response_time_ms' => defined('LARAVEL_START') ? microtime(true) - LARAVEL_START : 0,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'timestamp' => now()->toISOString(),
            'error' => $e->getMessage(),
        ], 503);
    }
})->name('health');

// --- MONITORING & ANALYTICS ---
// Protected monitoring endpoints (require authentication for sensitive data)
Route::middleware('auth:sanctum')->prefix('monitoring')->name('api.monitoring.')->group(function () {
    Route::get('/performance', [MonitoringController::class, 'performance'])->name('performance');
    Route::get('/analytics', [MonitoringController::class, 'analytics'])->name('analytics');
    Route::get('/products/{productId}/analytics', [MonitoringController::class, 'productAnalytics'])->name('products.analytics');
    Route::post('/sync-metal-prices', [MonitoringController::class, 'syncMetalPrices'])->name('sync-metal-prices');
    Route::get('/latest-metal-prices', [MonitoringController::class, 'latestMetalPrices'])->name('latest-metal-prices');
});

Route::get('/image-proxy', function (Request $request) {
    $url = $request->query('url');
    if (!$url) {
        return response()->json(['error' => 'URL parameter is missing.'], 400);
    }
    if (!Str::startsWith($url, ['https://m.media-amazon.com/', 'http://m.media-amazon.com/'])) {
        return response()->json(['error' => 'Invalid image URL provided.'], 403);
    }
    try {
        $response = Http::timeout(10)->get($url);
        if ($response->successful()) {
            return response($response->body())
                ->header('Content-Type', $response->header('Content-Type') ?: 'image/jpeg');
        }
        return response()->json(['error' => 'Failed to fetch image from Amazon.'], $response->status());
    } catch (\Exception $e) {
        Log::error('Image Proxy Error: ' . $e->getMessage(), ['url' => $url]);
        return response()->json(['error' => 'Error fetching image.'], 500);
    }
})->name('api.image-proxy');

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 1. Standard Laravel Auth Routes are disabled by default because the public customer-facing
//    SPA handles /login, /register, /forgot-password and React uses the API auth endpoints.
//    Leaving these enabled would cause direct browser navigation to /login to hit Laravel's
//    Blade auth pages instead of serving the React application.
// For the test environment we enable the standard auth routes so feature tests can exercise
// the built-in authentication, verification and password reset flows.
if (app()->environment('testing') && file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}

// Register profile routes for the testing environment so feature tests
// that expect the Breeze-style profile endpoints work without enabling
// the full Blade auth scaffolding in non-test environments.
if (app()->environment('testing')) {
    // Provide a simple named 'home' route used by some Blade partials during tests.
    Route::get('/', function () {
        return '';
    })->name('home');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    });
}

Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// 2. Any other specific Laravel backend web routes should go here.
//    For example, if you had a traditional Blade view for a homepage:
//    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// --- FILAMENT ROUTES ARE USUALLY AUTO-REGISTERED BY ITS SERVICE PROVIDER ---
// You typically DO NOT need to define Filament's core routes here.
// The AdminPanelProvider (or your equivalent) handles registering routes like /admin, /admin/login etc.
// These routes are usually registered with a prefix (e.g., 'admin').

// 3. API routes for your React SPA (if any are served by Laravel and not caught by Filament)
//    These should ideally be in routes/api.php, but if you have web-based API-like routes,
//    they would go here.
Route::get('/storage/{filePath}', function ($filePath) {
    // We explicitly tell Laravel to use the 'public' disk, which
    // correctly points to the 'storage/app/public' directory.
    // The $filePath variable (e.g., 'try_on_images/image.jpg') is the correct relative path.

    if (!Storage::disk('public')->exists($filePath)) {
        abort(404);
    }

    $file = Storage::disk('public')->get($filePath);
    $type = Storage::disk('public')->mimeType($filePath);

    return response($file)->header('Content-Type', $type);

})->where('filePath', '.*');
// 4. React SPA Catch-All Route (MUST BE LAST for web routes)
//    This ensures that any web route not matched above will be handled by React.
//    IMPORTANT: when using a PWA service worker, /admin must also be denylisted
//    in the generated sw.js navigation route so the browser does not rewrite
//    admin navigations to the SPA shell.
Route::get('/{any?}', function () {
    $indexFile = public_path('index.html');

    if (!file_exists($indexFile)) {
        return response(
            'React application entry point not found in public directory. Build the frontend assets and deploy them to backend/public.',
            503
        );
    }

    return response()->file($indexFile, ['Content-Type' => 'text/html']);
})->where(
    'any',
    '^(?!admin(?:\/|$)|filament(?:\/|$)|livewire(?:\/|$)|api(?:\/|$)|sanctum(?:\/|$)|storage(?:\/|$)|telescope(?:\/|$)|horizon(?:\/|$)|nova-api(?:\/|$)|nova-vendor(?:\/|$)|css(?:\/|$)|js(?:\/|$)|build(?:\/|$)|assets(?:\/|$)|images(?:\/|$)|fonts(?:\/|$)|vendor(?:\/|$)).*$'
) // IMPORTANT: exclude admin/filament and static asset prefixes
    ->name('react.spa');

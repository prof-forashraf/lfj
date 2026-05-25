<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\AffiliateProduct; // Assuming you might feature these

class HomeController extends Controller
{
    public function index()
    {
        $latestPosts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->with(['author', 'categories']) // Eager load for performance
            ->take(6) // Show more posts if your design allows
            ->get();

        $featuredCategories = Category::where('is_featured', true) // <-- USES THE NEW FLAG
            ->withCount('posts')
            ->orderBy('name') // Or by some other order you might add
            ->take(4) // Or however many you want to show
            ->get();

        // Example: Fetching some affiliate products marked as "featured"
        $featuredProducts = AffiliateProduct::where('status', 'active')
            // ->where('is_promoted_on_home', true) // Add a flag like this in your model/Filament
            ->take(4)
            ->get();

        return view('home', [
            'latestPosts' => $latestPosts,
            'featuredCategories' => $featuredCategories,
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
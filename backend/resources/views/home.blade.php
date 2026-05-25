@extends('layouts.app')

@section('title', 'Stunning Fashion Jewellery - Trends, Guides & Amazon Finds')
@section('meta_description', 'Explore the latest in fashion jewellery at LatestFashionJewellery.com. Discover beautiful necklaces, rings, earrings, and more. Get style inspiration and find curated Amazon affiliate products.')
{{-- Add more specific OG/Twitter tags if needed --}}

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-soft-cream via-pink-50 to-accent-rose/30 min-h-[60vh] md:min-h-[80vh] flex items-center justify-center text-center overflow-hidden py-12 md:py-0">
        <div class="absolute inset-0 opacity-20" style="background-image: url('{{ asset('images/hero-bg-pattern.svg') }}'); background-size: cover;"></div> {{-- Subtle pattern --}}
        <div class="relative z-10 p-6 max-w-3xl mx-auto">
            <h1 class="font-playfair text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-dark-slate mb-6 animate-fade-in-down">
                Discover Your <span class="text-primary-gold">Signature Sparkle</span>
            </h1>
            <p class="font-lato text-lg md:text-xl text-gray-700 max-w-2xl mx-auto mb-10 animate-fade-in-up" style="animation-delay: 0.3s;">
                Unveiling the latest trends, timeless classics, and hidden gems in fashion jewellery. Curated for elegance, designed for you.
            </p>
            <div class="space-y-4 sm:space-y-0 sm:space-x-4 animate-fade-in-up" style="animation-delay: 0.6s;">
                <a href="{{ route('blog.index') }}"
                   class="inline-block bg-primary-gold hover:bg-opacity-80 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out text-lg">
                    Explore Our Blog
                </a>
                <a href="#" {{-- Link to Virtual Try-On page later --}}
                   class="inline-block bg-white hover:bg-soft-cream text-primary-gold border-2 border-primary-gold font-semibold py-3 px-8 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out text-lg">
                    Virtual Try-On <span class="ml-1">→</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Categories Section -->
    @if($featuredCategories && $featuredCategories->count())
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-playfair font-bold text-dark-slate">Shop by Category</h2>
                <p class="mt-2 text-lg text-gray-600">Find the perfect piece for any occasion.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($featuredCategories as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="group block bg-soft-cream rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                    {{-- <div class="aspect-w-4 aspect-h-3"> --}}
                    {{-- You'll need a way to associate images with categories, or use placeholders --}}
                    {{-- <img src="{{ $category->image_url ?? asset('images/placeholder-category.jpg') }}" alt="{{ $category->name }}" class="object-cover w-full h-48 group-hover:scale-105 transition-transform duration-300"> --}}
                    {{-- </div> --}}
                    <div class="h-48 bg-gray-200 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                         <img src="{{ asset('images/category-placeholder.png') }}" alt="{{ $category->name }}" class="w-24 h-24 opacity-50"> {{-- Placeholder --}}
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-playfair font-semibold text-dark-slate group-hover:text-primary-gold transition-colors">{{ $category->name }}</h3>
                        <span class="text-xs text-gray-500">{{ $category->posts_count }} {{ Str::plural('Article', $category->posts_count) }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Latest Blog Posts Section -->
    @if($latestPosts && $latestPosts->count())
    <section class="py-16 lg:py-24 bg-soft-cream">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-playfair font-bold text-dark-slate">From Our Blog</h2>
                <p class="mt-2 text-lg text-gray-600">Latest insights, trends, and style guides.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                @foreach($latestPosts as $post)
                    @include('components.blog-post-card', ['post' => $post]) {{-- Using a component --}}
                @endforeach
            </div>
            <div class="mt-16 text-center">
                 <a href="{{ route('blog.index') }}"
                   class="bg-primary-gold hover:bg-opacity-80 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out text-lg">
                    View All Posts
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Virtual Try-On Teaser -->
    <section class="py-16 lg:py-24 bg-white text-center">
        <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8">
             <h2 class="text-3xl lg:text-4xl font-playfair font-bold text-dark-slate">Try It Before You Imagine It!</h2>
             <p class="mt-4 text-lg text-gray-600 mb-8">
                Experience our innovative Virtual Try-On feature. See how stunning our curated jewellery pieces look on you, right from your screen.
             </p>
             <a href="#" {{-- Link to Virtual Try-On page --}}
                   class="bg-accent-rose hover:opacity-80 text-white font-semibold py-4 px-10 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out text-xl">
                    Start Virtual Try-On
             </a>
        </div>
    </section>

    <!-- Featured Affiliate Products (Example Structure) -->
    @if($featuredProducts && $featuredProducts->count())
    <section class="py-16 lg:py-24 bg-soft-cream">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-playfair font-bold text-dark-slate">Our Jewellery Picks</h2>
                <p class="mt-2 text-lg text-gray-600">Handpicked selections we think you'll love.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($featuredProducts as $product)
                <div class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1 p-2">
                    <div class="relative aspect-square bg-gray-100 rounded-lg overflow-hidden">
                        @if($product->main_image_url_snapshot)
                        <img src="{{ $product->main_image_url_snapshot }}" alt="{{ $product->product_name_snapshot }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                        @else
                        <img src="{{ asset('images/product-placeholder.png') }}" alt="Placeholder" class="w-full h-full object-contain opacity-50 p-8">
                        @endif
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-md font-semibold text-dark-slate h-12 line-clamp-2 mb-2">{{ $product->product_name_snapshot }}</h3>
                        {{-- DO NOT DISPLAY PRICE HERE UNLESS LIVE FROM API --}}
                        {{-- <p class="text-primary-gold font-bold text-lg mb-3">$XX.XX</p> --}}
                        @if($product->amazon_url)
                        <a href="{{ $product->amazon_url }}" target="_blank" rel="noopener sponsored"
                           class="inline-block mt-2 bg-primary-gold text-white text-sm font-semibold py-2 px-6 rounded-md hover:bg-opacity-80 transition-colors">
                            View on Amazon
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

@endsection

@push('styles')
{{-- You can put these animations in resources/css/app.css if preferred --}}
<style>
    @keyframes fadeInDown { 0% { opacity: 0; transform: translateY(-20px); } 100% { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-down { animation: fadeInDown 0.8s ease-out forwards; }
    .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
</style>
@endpush
@extends('layouts.app')

@section('title', 'Blog - Jewellery Trends, Insights & Style Guides')
@section('meta_description', 'Explore the LatestFashionJewellery.com blog for expert articles on jewellery trends, care tips, buying guides, and stunning Amazon affiliate product features.')
@section('canonical_url', route('blog.index'))

@section('content')
    <div class="bg-soft-cream py-12 md:py-16">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <header class="text-center mb-12 md:mb-16">
                <h1 class="text-4xl lg:text-5xl font-playfair font-bold text-dark-slate">Our Jewellery Journal</h1>
                <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">Insights, inspiration, and the latest buzz from the world of fashion jewellery.</p>
            </header>

            @if($posts && $posts->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                    @foreach($posts as $post)
                        <x-blog-post-card :post="$post" />
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $posts->links() }} {{-- Tailwind styled pagination --}}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No Posts Yet!</h3>
                    <p class="mt-1 text-sm text-gray-500">We're busy crafting some sparkling content. Check back soon!</p>
                </div>
            @endif
        </div>
    </div>
@endsection
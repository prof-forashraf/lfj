@extends('layouts.app')

@section('title', 'Category: ' . $category->name)
@section('meta_description', $category->meta_description ?: 'Posts categorized under ' . $category->name . ' at LatestFashionJewellery.com')
@section('canonical_url', route('category.show', $category->slug))

@section('content')
<div class="bg-soft-cream py-12 md:py-16">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="text-center mb-12 md:mb-16">
            <h1 class="text-4xl lg:text-5xl font-playfair font-bold text-dark-slate">Category: {{ $category->name }}</h1>
            @if($category->description)
                <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">{{ $category->description }}</p>
            @endif
        </header>

        @if($posts && $posts->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                @foreach($posts as $post)
                    <x-blog-post-card :post="$post" />
                @endforeach
            </div>

            <div class="mt-16">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <p class="text-lg text-gray-600">No posts found in the "{{ $category->name }}" category yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
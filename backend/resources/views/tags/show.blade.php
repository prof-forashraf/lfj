@extends('layouts.app')

@section('title', 'Posts tagged with: ' . $tag->name)
@section('meta_description', 'Discover posts tagged with ' . $tag->name . ' on LatestFashionJewellery.com. Explore related jewellery topics and trends.')
@section('canonical_url', route('tag.show', $tag->slug))

@section('content')
<div class="bg-soft-cream py-12 md:py-16">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="text-center mb-12 md:mb-16">
            <h1 class="text-4xl lg:text-5xl font-playfair font-bold text-dark-slate">Tagged: {{ $tag->name }}</h1>
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
                <p class="text-lg text-gray-600">No posts found with the tag "{{ $tag->name }}" yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
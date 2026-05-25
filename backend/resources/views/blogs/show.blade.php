@extends('layouts.app')

{{-- SEO Meta Tags specific to this post --}}
@section('title', $post->seo_title ?: $post->title)
@section('meta_description', $post->meta_description ?: $post->excerpt ?: Illuminate\Support\Str::limit(strip_tags($post->content), 155))
@section('canonical_url', route('blog.show', $post->slug))
@section('og_type', 'article')
@section('og_title', $post->seo_title ?: $post->title)
@section('og_description', $post->meta_description ?: $post->excerpt ?: Illuminate\Support\Str::limit(strip_tags($post->content), 155))
@if($post->featured_image)
    @section('og_image', asset('storage/' . $post->featured_image))
    @section('twitter_image', asset('storage/' . $post->featured_image))
@endif

@push('seo_meta_tags')
    <meta property="article:published_time" content="{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    @if($post->author)
    <meta property="article:author" content="{{ $post->author->name }}">
    @endif
    @foreach($post->tags as $tag)
    <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach
@endpush


@section('content')
<div class="bg-white py-12 md:py-16">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <article class="prose prose-lg lg:prose-xl prose-headings:font-playfair prose-a:text-primary-gold hover:prose-a:text-opacity-80 max-w-none">
            <header class="mb-8 md:mb-12">
                @if($post->categories->isNotEmpty())
                <div class="mb-4">
                    @foreach($post->categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="text-sm font-semibold text-primary-gold hover:text-opacity-80 uppercase tracking-wider">
                            {{ $category->name }}
                        </a>
                        @if(!$loop->last), @endif
                    @endforeach
                </div>
                @endif

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-dark-slate !mb-4">{{ $post->title }}</h1> {{-- !mb-4 to override prose --}}

                <p class="text-gray-600 text-base">
                    Published on {{ $post->published_at ? $post->published_at->format('F j, Y') : $post->created_at->format('F j, Y') }}
                    @if($post->author)
                        by <a href="#" class="font-medium hover:underline">{{ $post->author->name }}</a>
                    @endif
                </p>
            </header>

            @if($post->featured_image)
                <figure class="mb-8 rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
                </figure>
            @endif

            {{-- Main Content --}}
            <div class="prose-img:rounded-lg prose-img:shadow-md"> {{-- Style images within content --}}
                {!! $post->content !!} {{-- Make sure content is sanitized if coming from user input not fully trusted --}}
            </div>

            <footer class="mt-10 pt-8 border-t border-gray-200">
                @if($post->tags->isNotEmpty())
                <div class="mb-6">
                    <span class="font-semibold">Tags:</span>
                    @foreach($post->tags as $tag)
                        <a href="{{ route('tag.show', $tag->slug) }}" class="ml-2 inline-block bg-gray-100 text-gray-700 text-sm font-medium px-3 py-1 rounded-full hover:bg-gray-200 transition-colors">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
                @endif

                {{-- Social Share (Simple Links - for proper sharing use JS SDKs or libraries) --}}
                <div class="flex items-center space-x-4">
                    <span class="font-semibold">Share this post:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" class="text-gray-500 hover:text-blue-600">Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" class="text-gray-500 hover:text-sky-500">Twitter</a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.show', $post->slug)) }}&title={{ urlencode($post->title) }}" target="_blank" class="text-gray-500 hover:text-blue-700">LinkedIn</a>
                </div>
            </footer>
        </article>

        {{-- Related Posts --}}
        @if(isset($relatedPosts) && $relatedPosts->count())
            <section class="mt-16 pt-12 border-t border-gray-200">
                <h2 class="text-2xl md:text-3xl font-playfair font-bold text-dark-slate mb-8">You Might Also Like</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                    @foreach($relatedPosts as $relatedPost)
                        <x-blog-post-card :post="$relatedPost" />
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</div>
@endsection
{{-- resources/views/components/blog-post-card.blade.php --}}
@props(['post'])

<article class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
    @if($post->featured_image)
        <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden aspect-[16/10]"> {{-- Aspect ratio for consistency --}}
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        </a>
    @else
        <a href="{{ route('blog.show', $post->slug) }}" class="aspect-[16/10] bg-gray-200 flex items-center justify-center">
            <img src="{{ asset('images/post-placeholder.png ') }}" alt="Placeholder" class="w-24 h-24 opacity-50"> {{-- Placeholder --}}
        </a>
    @endif
    <div class="p-6 flex flex-col flex-grow">
        @if($post->categories->isNotEmpty())
        <div class="mb-2">
            @foreach($post->categories->take(2) as $category) {{-- Show max 2 categories --}}
                <a href="{{ route('category.show', $category->slug) }}" class="inline-block bg-accent-rose/20 text-accent-rose text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full hover:bg-accent-rose/30 transition-colors">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
        @endif
        <h3 class="text-xl font-playfair font-semibold mb-2 text-dark-slate">
            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-primary-gold transition-colors line-clamp-2">{{ $post->title }}</a>
        </h3>
        <p class="text-gray-500 text-sm mb-3">
            {{ $post->published_at ? $post->published_at->format('F j, Y') : '' }}
            @if($post->author)
            <span class="mx-1">•</span> By <a href="#" class="hover:text-primary-gold">{{ $post->author->name }}</a> {{-- Link to author archive later --}}
            @endif
        </p>
        <p class="text-gray-600 mb-4 flex-grow line-clamp-3 leading-relaxed">
            {{ $post->excerpt ?: Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}
        </p>
        <a href="{{ route('blog.show', $post->slug) }}" class="inline-block mt-auto text-primary-gold hover:text-opacity-80 font-semibold transition-colors self-start">
            Read More <span aria-hidden="true">→</span>
        </a>
    </div>
</article>  
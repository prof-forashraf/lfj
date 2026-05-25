<?php

namespace App\Services;

use App\Models\AffiliateProduct;
use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SeoService
{
    public function forPost(Post $post): array
    {
        $canonical = $post->canonical_url ?: $this->frontendUrl('/blog/' . $post->slug);
        $title = $post->effective_seo_title;
        $description = $post->effective_meta_description;
        $image = $this->absoluteUrl($post->effective_og_image);

        return $this->shape([
            'title' => $title,
            'meta_description' => $description,
            'keywords' => $this->keywords($post->meta_keywords),
            'canonical' => $canonical,
            'robots' => $this->robots((bool) $post->noindex, (bool) $post->nofollow),
            'og_title' => $post->og_title ?: $title,
            'og_description' => $post->og_description ?: $description,
            'og_image' => $image,
            'og_type' => 'article',
            'og_url' => $canonical,
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $post->twitter_title ?: $title,
            'twitter_description' => $post->twitter_description ?: $description,
            'twitter_image' => $image,
            'schema' => [
                $this->buildArticleSchema($post),
                $this->buildBreadcrumbSchema([
                    ['name' => 'Home', 'url' => $this->frontendUrl('/')],
                    ['name' => 'Blog', 'url' => $this->frontendUrl('/blog')],
                    ['name' => $post->title, 'url' => $canonical],
                ]),
            ],
            'reading_time' => $post->reading_time,
            'published_at' => $post->published_at?->toIso8601String(),
            'modified_at' => $post->updated_at?->toIso8601String(),
        ]);
    }

    public function forCategory(Category $category): array
    {
        $canonical = $this->frontendUrl('/category/' . $category->slug);
        $title = $category->effective_seo_title;
        $description = $category->effective_meta_description;
        $image = $this->absoluteUrl($category->effective_og_image ?: config('seo.default_og_image'));

        return $this->shape([
            'title' => $title,
            'meta_description' => $description,
            'keywords' => '',
            'canonical' => $canonical,
            'robots' => 'index,follow',
            'og_title' => $category->og_title ?: $title,
            'og_description' => $category->og_description ?: $description,
            'og_image' => $image,
            'og_type' => 'website',
            'og_url' => $canonical,
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $category->og_title ?: $title,
            'twitter_description' => $category->og_description ?: $description,
            'twitter_image' => $image,
            'schema' => [
                $this->buildCollectionSchema($title, $description, $canonical, $category->schema_type ?: 'CollectionPage'),
                $this->buildBreadcrumbSchema([
                    ['name' => 'Home', 'url' => $this->frontendUrl('/')],
                    ['name' => 'Blog', 'url' => $this->frontendUrl('/blog')],
                    ['name' => $category->name, 'url' => $canonical],
                ]),
            ],
        ]);
    }

    public function forEvent(Event $event): array
    {
        $canonical = $this->frontendUrl('/events/' . $event->slug);
        $title = $event->effective_seo_title;
        $description = $event->effective_meta_description;
        $image = $this->absoluteUrl($event->effective_og_image);

        return $this->shape([
            'title' => $title,
            'meta_description' => $description,
            'keywords' => '',
            'canonical' => $canonical,
            'robots' => 'index,follow',
            'og_title' => $event->og_title ?: $title,
            'og_description' => $event->og_description ?: $description,
            'og_image' => $image,
            'og_type' => 'event',
            'og_url' => $canonical,
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $event->og_title ?: $title,
            'twitter_description' => $event->og_description ?: $description,
            'twitter_image' => $image,
            'schema' => [
                $this->buildEventSchema($event),
                $this->buildBreadcrumbSchema([
                    ['name' => 'Home', 'url' => $this->frontendUrl('/')],
                    ['name' => 'Events', 'url' => $this->frontendUrl('/events')],
                    ['name' => $event->title, 'url' => $canonical],
                ]),
            ],
            'published_at' => $event->created_at?->toIso8601String(),
            'modified_at' => $event->updated_at?->toIso8601String(),
        ]);
    }

    public function forTag(Tag $tag): array
    {
        $canonical = $this->frontendUrl('/tag/' . $tag->slug);
        $title = $tag->effective_seo_title;
        $description = $tag->effective_meta_description;

        return $this->shape([
            'title' => $title,
            'meta_description' => $description,
            'keywords' => $tag->name,
            'canonical' => $canonical,
            'robots' => 'index,follow',
            'og_title' => $tag->effectiveOgTitle,
            'og_description' => $tag->effectiveOgDescription,
            'og_image' => $this->absoluteUrl($tag->og_image ?: config('seo.default_og_image')),
            'og_type' => 'website',
            'og_url' => $canonical,
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $tag->effectiveOgTitle,
            'twitter_description' => $tag->effectiveOgDescription,
            'twitter_image' => $this->absoluteUrl($tag->og_image ?: config('seo.default_og_image')),
            'schema' => [
                $this->buildCollectionSchema($title, $description, $canonical, $tag->schema_type ?: 'CollectionPage'),
                $this->buildBreadcrumbSchema([
                    ['name' => 'Home', 'url' => $this->frontendUrl('/')],
                    ['name' => 'Blog', 'url' => $this->frontendUrl('/blog')],
                    ['name' => 'Tags', 'url' => $this->frontendUrl('/tags')],
                    ['name' => $tag->name, 'url' => $canonical],
                ]),
            ],
        ]);
    }

    public function forProduct(AffiliateProduct $product): array
    {
        $canonical = $this->frontendUrl('/products/' . $product->amazon_asin);
        $title = $product->effective_seo_title;
        $description = $product->effective_meta_description;
        $image = $this->absoluteUrl($product->effective_og_image);

        return $this->shape([
            'title' => $title,
            'meta_description' => $description,
            'keywords' => '',
            'canonical' => $canonical,
            'robots' => 'index,follow',
            'og_title' => $product->og_title ?: $title,
            'og_description' => $product->og_description ?: $description,
            'og_image' => $image,
            'og_type' => 'product',
            'og_url' => $canonical,
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $product->og_title ?: $title,
            'twitter_description' => $product->og_description ?: $description,
            'twitter_image' => $image,
            'schema' => [$this->buildProductSchema($product)],
        ]);
    }

    public function forVideo(Video $video): array
    {
        $canonical = $this->frontendUrl('/videos/' . $video->id);
        $title = $video->effective_seo_title;
        $description = $video->effective_meta_description;
        $image = $this->absoluteUrl($video->effective_og_image);

        return $this->shape([
            'title' => $title,
            'meta_description' => $description,
            'keywords' => '',
            'canonical' => $canonical,
            'robots' => 'index,follow',
            'og_title' => $video->og_title ?: $title,
            'og_description' => $video->og_description ?: $description,
            'og_image' => $image,
            'og_type' => 'video.other',
            'og_url' => $canonical,
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $video->og_title ?: $title,
            'twitter_description' => $video->og_description ?: $description,
            'twitter_image' => $image,
            'schema' => [$this->buildVideoSchema($video)],
        ]);
    }

    public function forPage(string $page): array
    {
        $data = config("seo.pages.$page", config('seo.pages.home'));
        $title = $this->formatTitle($data['title']);
        $canonical = $this->frontendUrl($data['path']);
        $schema = [];

        foreach ($data['schemas'] ?? [] as $schemaType) {
            $schema[] = match ($schemaType) {
                'website' => $this->buildWebsiteSchema(),
                'organization' => $this->buildOrganizationSchema(),
                'collection' => $this->buildCollectionSchema($title, $data['description'], $canonical),
                default => null,
            };
        }

        return $this->shape([
            'title' => $title,
            'meta_description' => Str::limit($data['description'], 160, ''),
            'keywords' => '',
            'canonical' => $canonical,
            'robots' => 'index,follow',
            'og_title' => $title,
            'og_description' => Str::limit($data['description'], 160, ''),
            'og_image' => config('seo.default_og_image'),
            'og_type' => $data['type'] ?? 'website',
            'og_url' => $canonical,
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $title,
            'twitter_description' => Str::limit($data['description'], 160, ''),
            'twitter_image' => config('seo.default_og_image'),
            'schema' => array_values(array_filter($schema)),
        ]);
    }

    public function buildArticleSchema(Post $post): array
    {
        $canonical = $post->canonical_url ?: $this->frontendUrl('/blog/' . $post->slug);

        return [
            '@context' => 'https://schema.org',
            '@type' => $post->schema_type ?: 'Article',
            'headline' => $post->effective_seo_title,
            'description' => $post->effective_meta_description,
            'image' => $this->absoluteUrl($post->effective_og_image),
            'author' => ['@type' => 'Person', 'name' => $post->author?->name ?: config('seo.site_name')],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('seo.site_name'),
                'logo' => ['@type' => 'ImageObject', 'url' => config('seo.logo_url')],
            ],
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'mainEntityOfPage' => $canonical,
            'keywords' => Arr::wrap($post->meta_keywords),
            'wordCount' => str_word_count(strip_tags((string) $post->content)),
            'timeRequired' => 'PT' . max(1, (int) $post->reading_time) . 'M',
        ];
    }

    public function buildEventSchema(Event $event): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->title,
            'startDate' => $event->start_datetime?->toIso8601String(),
            'endDate' => $event->end_datetime?->toIso8601String(),
            'location' => [
                '@type' => 'Place',
                'name' => $event->location_name,
                'address' => $event->location_address,
            ],
            'image' => $this->absoluteUrl($event->effective_og_image),
            'url' => $event->event_url ?: $this->frontendUrl('/events/' . $event->slug),
            'description' => $event->effective_meta_description,
            'eventStatus' => $this->eventStatus($event->status),
        ];
    }

    public function buildProductSchema(AffiliateProduct $product): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->product_name_snapshot,
            'image' => $this->absoluteUrl($product->effective_og_image),
            'description' => $product->effective_meta_description,
            'offers' => [
                '@type' => 'Offer',
                'url' => $product->amazon_url,
                'availability' => 'https://schema.org/InStock',
            ],
        ];
    }

    public function buildVideoSchema(Video $video): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $video->title,
            'description' => $video->effective_meta_description,
            'thumbnailUrl' => $video->youtube_thumbnail_url,
            'uploadDate' => $video->created_at?->toIso8601String(),
            'embedUrl' => $video->youtube_embed_url,
            'contentUrl' => 'https://www.youtube.com/watch?v=' . $video->youtube_video_id,
        ];
    }

    public function buildBreadcrumbSchema(array $crumbs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($crumbs)->values()->map(fn ($crumb, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['name'] ?? $crumb['label'] ?? '',
                'item' => $crumb['url'] ?? $crumb['href'] ?? '',
            ])->all(),
        ];
    }

    public function buildWebsiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('seo.site_name'),
            'url' => config('seo.site_url'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $this->frontendUrl('/search?q={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    public function buildOrganizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('seo.site_name'),
            'url' => config('seo.site_url'),
            'logo' => config('seo.logo_url'),
            'sameAs' => array_values(array_filter(config('seo.social_profiles', []))),
        ];
    }

    public function buildCollectionSchema(string $name, ?string $description, string $url, string $type = 'CollectionPage'): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => $type,
            'name' => $name,
            'description' => $description,
            'url' => $url,
        ];
    }

    private function shape(array $data): array
    {
        return [
            'title' => $data['title'],
            'meta_description' => $data['meta_description'],
            'keywords' => $data['keywords'],
            'canonical' => $data['canonical'],
            'robots' => $data['robots'],
            'og' => [
                'title' => $data['og_title'],
                'description' => $data['og_description'],
                'image' => $data['og_image'],
                'type' => $data['og_type'],
                'url' => $data['og_url'],
            ],
            'twitter' => [
                'card' => $data['twitter_card'],
                'title' => $data['twitter_title'],
                'description' => $data['twitter_description'],
                'image' => $data['twitter_image'],
            ],
            'schema' => $data['schema'] ?? [],
            'reading_time' => $data['reading_time'] ?? null,
            'published_at' => $data['published_at'] ?? null,
            'modified_at' => $data['modified_at'] ?? null,
            'site_name' => config('seo.site_name'),
            'twitter_handle' => config('seo.twitter_handle'),
            'theme_color' => config('seo.theme_color'),
        ];
    }

    private function formatTitle(string $title): string
    {
        return str_replace(
            ['{Page Title}', '{Site Name}'],
            [$title, config('seo.site_name')],
            config('seo.default_title')
        );
    }

    private function keywords(mixed $keywords): string
    {
        if (is_array($keywords)) {
            return implode(', ', array_filter($keywords));
        }

        return (string) $keywords;
    }

    private function robots(bool $noindex, bool $nofollow): string
    {
        return ($noindex ? 'noindex' : 'index') . ',' . ($nofollow ? 'nofollow' : 'follow');
    }

    private function frontendUrl(string $path): string
    {
        return rtrim(config('seo.frontend_url') ?: config('app.url'), '/') . '/' . ltrim($path, '/');
    }

    private function absoluteUrl(?string $url): string
    {
        $url = $url ?: config('seo.default_og_image');

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        return rtrim(config('app.url'), '/') . '/' . ltrim($url, '/');
    }

    private function eventStatus(?string $status): string
    {
        return match ($status) {
            'cancelled', 'canceled' => 'https://schema.org/EventCancelled',
            'postponed' => 'https://schema.org/EventPostponed',
            default => 'https://schema.org/EventScheduled',
        };
    }
}

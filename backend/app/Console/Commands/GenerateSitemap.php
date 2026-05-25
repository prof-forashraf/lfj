<?php

namespace App\Console\Commands;

use App\Models\AffiliateProduct;
use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the public XML sitemap.';

    private int $entryCount = 0;

    public function handle(): int
    {
        $sitemap = $this->buildSitemap();
        $path = public_path('sitemap.xml');

        $sitemap->writeToFile($path);
        $this->pingSearchEngines();

        Log::info('Sitemap generated.', [
            'path' => $path,
            'entry_count' => $this->entryCount,
        ]);

        $this->info("Sitemap generated with {$this->entryCount} URLs.");

        return self::SUCCESS;
    }

    public function buildSitemap(): Sitemap
    {
        $this->entryCount = 0;
        $sitemap = Sitemap::create();

        $this->add($sitemap, '/', now(), config('seo.sitemap.homepage_changefreq'), config('seo.sitemap.homepage_priority'));
        $this->add($sitemap, '/blog', now(), 'daily', 0.9);

        if (config('seo.sitemap.include_posts')) {
            Post::query()->where('status', 'published')->with(['author'])->latest('updated_at')->chunk(100, function ($posts) use ($sitemap) {
                foreach ($posts as $post) {
                    $url = $this->url('/blog/' . $post->slug, $post->updated_at, config('seo.sitemap.posts_changefreq'), config('seo.sitemap.posts_priority'));
                    if ($post->effective_og_image) {
                        $url->addImage($post->effective_og_image, $post->effective_meta_description, '', $post->effective_seo_title);
                    }
                    $sitemap->add($url);
                    $this->entryCount++;
                }
            });
        }

        if (config('seo.sitemap.include_categories')) {
            Category::query()->latest('updated_at')->chunk(100, function ($categories) use ($sitemap) {
                foreach ($categories as $category) {
                    $url = $this->url('/category/' . $category->slug, $category->updated_at, config('seo.sitemap.categories_changefreq'), 0.7);
                    if ($category->effective_og_image) {
                        $url->addImage($category->effective_og_image, $category->effective_meta_description, '', $category->effective_seo_title);
                    }
                    $sitemap->add($url);
                    $this->entryCount++;
                }
            });
        }

        if (config('seo.sitemap.include_tags')) {
            Tag::query()->latest('updated_at')->chunk(100, function ($tags) use ($sitemap) {
                foreach ($tags as $tag) {
                    $this->add($sitemap, '/tag/' . $tag->slug, $tag->updated_at, 'monthly', 0.5);
                }
            });
        }

        if (config('seo.sitemap.include_events')) {
            Event::query()->where('status', 'published')->latest('updated_at')->chunk(100, function ($events) use ($sitemap) {
                foreach ($events as $event) {
                    $url = $this->url('/events/' . $event->slug, $event->updated_at, 'weekly', 0.7);
                    if ($event->effective_og_image) {
                        $url->addImage($event->effective_og_image, $event->effective_meta_description, '', $event->effective_seo_title);
                    }
                    $sitemap->add($url);
                    $this->entryCount++;
                }
            });
        }

        if (config('seo.sitemap.include_products')) {
            AffiliateProduct::query()->latest('updated_at')->chunk(100, function ($products) use ($sitemap) {
                foreach ($products as $product) {
                    $url = $this->url('/products/' . $product->amazon_asin, $product->updated_at, 'weekly', 0.6);
                    if ($product->effective_og_image) {
                        $url->addImage($product->effective_og_image, $product->effective_meta_description, '', $product->effective_seo_title);
                    }
                    $sitemap->add($url);
                    $this->entryCount++;
                }
            });
        }

        if (config('seo.sitemap.include_videos')) {
            Video::query()->where('status', 'published')->latest('updated_at')->chunk(100, function ($videos) use ($sitemap) {
                foreach ($videos as $video) {
                    $this->add($sitemap, '/videos/' . $video->id, $video->updated_at, 'weekly', 0.6);
                }
            });
        }

        return $sitemap;
    }

    private function add(Sitemap $sitemap, string $path, $lastModified, string $changeFrequency, float $priority): void
    {
        $sitemap->add($this->url($path, $lastModified, $changeFrequency, $priority));
        $this->entryCount++;
    }

    private function url(string $path, $lastModified, string $changeFrequency, float $priority): Url
    {
        return Url::create(rtrim(config('seo.site_url'), '/') . '/' . ltrim($path, '/'))
            ->setLastModificationDate($lastModified ?: now())
            ->setChangeFrequency($changeFrequency)
            ->setPriority($priority);
    }

    private function pingSearchEngines(): void
    {
        $sitemapUrl = urlencode(rtrim(config('seo.site_url'), '/') . '/sitemap.xml');

        foreach ([
            'https://www.google.com/ping?sitemap=' . $sitemapUrl,
            'https://www.bing.com/ping?sitemap=' . $sitemapUrl,
        ] as $url) {
            try {
                Http::timeout(5)->get($url);
            } catch (\Throwable $exception) {
                Log::warning('Sitemap ping failed.', ['url' => $url, 'error' => $exception->getMessage()]);
            }
        }
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SeoHealthWidget extends Widget
{
    protected static string $view = 'filament.widgets.seo-health-widget';

    protected int|string|array $columnSpan = 'full';

    public function getStats(): array
    {
        return [
            ['metric' => 'Posts missing SEO title', 'count' => Post::whereNull('seo_title')->orWhere('seo_title', '')->count(), 'status' => 'WARN'],
            ['metric' => 'Posts missing meta description', 'count' => Post::whereNull('meta_description')->orWhere('meta_description', '')->count(), 'status' => 'WARN'],
            ['metric' => 'Posts missing featured image', 'count' => Post::whereNull('featured_image')->orWhere('featured_image', '')->count(), 'status' => 'WARN'],
            ['metric' => 'Posts with noindex enabled', 'count' => Post::where('noindex', true)->count(), 'status' => 'INFO'],
            ['metric' => 'Posts without focus keyword', 'count' => Post::whereNull('focus_keyword')->orWhere('focus_keyword', '')->count(), 'status' => 'INFO'],
            ['metric' => 'Categories missing meta description', 'count' => Category::whereNull('meta_description')->orWhere('meta_description', '')->count(), 'status' => 'WARN'],
            ['metric' => 'Events missing SEO title', 'count' => Event::whereNull('seo_title')->orWhere('seo_title', '')->count(), 'status' => 'WARN'],
        ];
    }

    public function fixMissingSeoTitles(): void
    {
        Post::whereNull('seo_title')->orWhere('seo_title', '')->chunkById(100, function ($posts) {
            foreach ($posts as $post) {
                $post->forceFill(['seo_title' => $post->title])->save();
            }
        });

        Notification::make()->title('Missing post SEO titles filled.')->success()->send();
    }

    public function regenerateSitemap(): void
    {
        Artisan::call('sitemap:generate');
        Notification::make()->title('Sitemap regenerated.')->success()->send();
    }
}

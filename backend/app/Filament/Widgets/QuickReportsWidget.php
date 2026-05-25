<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use Filament\Widgets\Widget;

class QuickReportsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-reports-widget';

    protected int|string|array $columnSpan = 'full';

    public function getReports(): array
    {
        return [
            [
                'label' => 'Pending review posts',
                'count' => Post::where('status', 'draft')->orWhere('status', 'pending')->count(),
                'color' => 'warning',
                'href' => url('/admin/resources/posts'),
            ],
            [
                'label' => 'Posts missing SEO title',
                'count' => Post::whereNull('seo_title')->orWhere('seo_title', '')->count(),
                'color' => 'danger',
                'href' => url('/admin/resources/posts'),
            ],
            [
                'label' => 'Posts missing meta description',
                'count' => Post::whereNull('meta_description')->orWhere('meta_description', '')->count(),
                'color' => 'danger',
                'href' => url('/admin/resources/posts'),
            ],
            [
                'label' => 'Categories missing meta description',
                'count' => Category::whereNull('meta_description')->orWhere('meta_description', '')->count(),
                'color' => 'warning',
                'href' => url('/admin/resources/categories'),
            ],
            [
                'label' => 'Events missing SEO title',
                'count' => Event::whereNull('seo_title')->orWhere('seo_title', '')->count(),
                'color' => 'warning',
                'href' => url('/admin/resources/events'),
            ],
        ];
    }

    public function getDailyUpdateLinks(): array
    {
        return [
            ['label' => 'Gold Prices', 'href' => url('/admin/resources/gold-prices'), 'color' => 'primary'],
            ['label' => 'Diamond Pricing', 'href' => url('/admin/resources/diamond-pricings'), 'color' => 'secondary'],
            ['label' => 'Daily Metal Prices', 'href' => url('/admin/resources/daily-metal-prices'), 'color' => 'success'],
            ['label' => 'Market Rates', 'href' => url('/admin/resources/market-rates'), 'color' => 'warning'],
        ];
    }
}

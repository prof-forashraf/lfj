<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use Filament\Widgets\Widget;

class ActionItemsWidget extends Widget
{
    protected static string $view = 'filament.widgets.action-items-widget';

    protected int|string|array $columnSpan = 'full';

    public function getActionItems(): array
    {
        return [
            [
                'label' => 'Pending review posts',
                'count' => Post::whereIn('status', ['draft', 'pending'])->count(),
                'href' => url('/admin/resources/posts'),
                'badge' => 'Review',
            ],
            [
                'label' => 'Posts missing SEO title',
                'count' => Post::whereNull('seo_title')->orWhere('seo_title', '')->count(),
                'href' => url('/admin/resources/posts'),
                'badge' => 'SEO',
            ],
            [
                'label' => 'Posts missing meta description',
                'count' => Post::whereNull('meta_description')->orWhere('meta_description', '')->count(),
                'href' => url('/admin/resources/posts'),
                'badge' => 'SEO',
            ],
            [
                'label' => 'Categories missing meta description',
                'count' => Category::whereNull('meta_description')->orWhere('meta_description', '')->count(),
                'href' => url('/admin/resources/categories'),
                'badge' => 'Taxonomy',
            ],
            [
                'label' => 'Events missing SEO title',
                'count' => Event::whereNull('seo_title')->orWhere('seo_title', '')->count(),
                'href' => url('/admin/resources/events'),
                'badge' => 'Event',
            ],
        ];
    }
}

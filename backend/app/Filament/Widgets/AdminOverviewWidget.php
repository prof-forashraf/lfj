<?php

namespace App\Filament\Widgets;

use App\Models\AffiliateProduct;
use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\Widget;

class AdminOverviewWidget extends Widget
{
    protected static string $view = 'filament.widgets.admin-overview-widget';

    protected int|string|array $columnSpan = 'full';

    public function getCounts(): array
    {
        return [
            ['label' => 'Published Posts', 'count' => Post::where('status', 'published')->count(), 'color' => 'success'],
            ['label' => 'Draft Posts', 'count' => Post::where('status', 'draft')->count(), 'color' => 'warning'],
            ['label' => 'Upcoming Events', 'count' => Event::where('status', 'upcoming')->count(), 'color' => 'primary'],
            ['label' => 'Affiliate Products', 'count' => AffiliateProduct::count(), 'color' => 'success'],
            ['label' => 'Categories', 'count' => Category::count(), 'color' => 'primary'],
            ['label' => 'Admin Users', 'count' => User::count(), 'color' => 'secondary'],
        ];
    }

    public function getQuickLinks(): array
    {
        return [
            ['label' => 'Create New Post', 'href' => url('/admin/resources/posts/create'), 'color' => 'primary'],
            ['label' => 'Create Event', 'href' => url('/admin/resources/events/create'), 'color' => 'secondary'],
            ['label' => 'Manage Categories', 'href' => url('/admin/resources/categories'), 'color' => 'success'],
            ['label' => 'SEO Health', 'href' => url('/admin/dashboard'), 'color' => 'warning'],
        ];
    }
}

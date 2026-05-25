<?php

return [
    'site_name' => env('SEO_SITE_NAME', config('app.name', 'Latest Fashion Jewellery')),
    'site_url' => env('APP_URL', 'http://localhost:8000'),
    'frontend_url' => env('FRONTEND_URL', env('APP_URL', 'http://localhost:8000')),
    'default_title' => '{Page Title} | {Site Name}',
    'title_separator' => ' | ',
    'default_description' => 'Latest Fashion Jewellery shares expert jewellery guides, events, styling ideas, videos, tools, and curated product inspiration.',
    'default_og_image' => env('SEO_DEFAULT_OG_IMAGE', env('APP_URL', 'http://localhost:8000') . '/images/post-placeholder.png'),
    'logo_url' => env('SEO_LOGO_URL', env('APP_URL', 'http://localhost:8000') . '/images/placeholder.png'),
    'twitter_handle' => env('SEO_TWITTER_HANDLE', '@latestfashionjewellery'),
    'facebook_app_id' => env('SEO_FACEBOOK_APP_ID', ''),
    'theme_color' => env('SEO_THEME_COLOR', '#b88a44'),

    'social_profiles' => [
        'twitter' => env('SEO_TWITTER_URL', 'https://twitter.com/latestfashionjewellery'),
        'instagram' => env('SEO_INSTAGRAM_URL', 'https://instagram.com/latestfashionjewellery'),
        'facebook' => env('SEO_FACEBOOK_URL', 'https://facebook.com/latestfashionjewellery'),
        'youtube' => env('SEO_YOUTUBE_URL', 'https://youtube.com/@latestfashionjewellery'),
        'pinterest' => env('SEO_PINTEREST_URL', 'https://pinterest.com/latestfashionjewellery'),
    ],

    'pages' => [
        'home' => [
            'title' => 'Latest Fashion Jewellery',
            'description' => 'Discover jewellery trends, care guides, event listings, videos, buying inspiration, and practical tools for jewellery lovers.',
            'path' => '/',
            'type' => 'website',
            'schemas' => ['website', 'organization'],
        ],
        'about' => [
            'title' => 'About',
            'description' => 'Learn about Latest Fashion Jewellery and our editorial approach to jewellery trends, education, and product discovery.',
            'path' => '/about',
            'type' => 'website',
            'schemas' => ['organization'],
        ],
        'contact' => [
            'title' => 'Contact',
            'description' => 'Contact Latest Fashion Jewellery for questions, editorial inquiries, and partnership opportunities.',
            'path' => '/contact',
            'type' => 'website',
            'schemas' => ['organization'],
        ],
        'blog-index' => [
            'title' => 'Blog',
            'description' => 'Read jewellery guides, styling inspiration, market explainers, and expert articles from Latest Fashion Jewellery.',
            'path' => '/blog',
            'type' => 'website',
            'schemas' => ['collection'],
        ],
        'events' => [
            'title' => 'Jewellery Events',
            'description' => 'Explore upcoming jewellery events, trade shows, exhibitions, and industry gatherings.',
            'path' => '/events',
            'type' => 'website',
            'schemas' => ['collection'],
        ],
        'videos' => [
            'title' => 'Jewellery Videos',
            'description' => 'Watch jewellery videos, guides, and inspiration from Latest Fashion Jewellery.',
            'path' => '/videos',
            'type' => 'website',
            'schemas' => ['collection'],
        ],
    ],

    'sitemap' => [
        'include_posts' => true,
        'include_categories' => true,
        'include_tags' => true,
        'include_events' => true,
        'include_products' => true,
        'include_videos' => true,
        'posts_changefreq' => 'weekly',
        'categories_changefreq' => 'weekly',
        'homepage_changefreq' => 'daily',
        'posts_priority' => 0.8,
        'homepage_priority' => 1.0,
    ],

    'robots' => [
        'disallow' => [
            '/admin',
            '/api/',
            '/storage/tmp/',
        ],
        'allow' => [
            '/storage/images/',
            '/storage/placeholders/',
        ],
        'blocked_agents' => [
            'GPTBot',
            'CCBot',
        ],
    ],
];

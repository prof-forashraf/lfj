<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Latest Fashion Jewellery') - {{ config('app.name', 'LatestFashionJewellery.com') }}</title>
    <meta name="description" content="@yield('meta_description', 'Discover the latest trends in fashion jewellery. Expert reviews, style guides, and curated affiliate product selections from Amazon.')">

    @hasSection('canonical_url')
        <link rel="canonical" href="@yield('canonical_url')" />
    @endif

    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', View::getSection('title', 'Latest Fashion Jewellery'))">
    <meta property="og:description" content="@yield('og_description', View::getSection('meta_description', 'Your source for jewellery trends.'))">
    <meta property="og:image" content="@yield('og_image', asset('images/default-social-image.jpg'))"> {{-- Create a default social image --}}

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', View::getSection('title', 'Latest Fashion Jewellery'))">
    <meta name="twitter:description" content="@yield('twitter_description', View::getSection('meta_description', 'Your source for jewellery trends.'))">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/default-social-image.jpg'))"> {{-- Create a default social image --}}


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"> {{-- Add your favicon.ico to public folder --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}"> {{-- Add apple-touch-icon.png --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}"> {{-- Add favicon-32x32.png --}}
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}"> {{-- Add favicon-16x16.png --}}
    <link rel="manifest" href="{{ asset('site.webmanifest') }}"> {{-- Add site.webmanifest for PWA features --}}


    <!-- Styles & Scripts -->
    @if (!app()->environment('testing'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
    @stack('styles')
</head>
<body class="font-lato text-dark-slate antialiased bg-soft-cream selection:bg-primary-gold selection:text-white">
    <div x-data="{ mobileMenuOpen: false }" class="min-h-screen flex flex-col">
        <!-- Navigation -->
        @include('layouts.partials.navbar')

        <!-- Page Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('layouts.partials.footer')
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
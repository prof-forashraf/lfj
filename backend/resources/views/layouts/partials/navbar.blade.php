@if (!app()->environment('testing'))
<header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 md:h-24">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" title="Latest Fashion Jewellery Homepage">
                    {{-- <img class="h-10 w-auto" src="{{ asset('images/logo.svg') }}" alt="LatestFashionJewellery.com"> --}}
                    <span class="text-2xl md:text-3xl font-playfair font-bold text-primary-gold hover:text-opacity-80 transition-colors">
                        LatestFashionJewellery
                    </span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex md:items-center md:space-x-6 lg:space-x-8">
                <a href="{{ route('home') }}" class="font-medium text-gray-600 hover:text-primary-gold transition-colors pb-1 border-b-2 {{ request()->routeIs('home') ? 'border-primary-gold text-primary-gold' : 'border-transparent' }}">Home</a>
                <a href="{{ route('blog.index') }}" class="font-medium text-gray-600 hover:text-primary-gold transition-colors pb-1 border-b-2 {{ request()->routeIs('blog.index*') ? 'border-primary-gold text-primary-gold' : 'border-transparent' }}">Blog</a>
                {{-- Placeholder for Categories Dropdown --}}
                <div x-data="{ open: false }" @mouseleave="open = false" class="relative">
                    <button @mouseover="open = true" class="font-medium text-gray-600 hover:text-primary-gold transition-colors pb-1 border-b-2 border-transparent focus:outline-none inline-flex items-center">
                        Categories <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute z-10 mt-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                         x-cloak>
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            {{-- Fetch and loop through top-level categories here later --}}
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-gold" role="menuitem">Necklaces (Example)</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-gold" role="menuitem">Rings (Example)</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-gold" role="menuitem">Earrings (Example)</a>
                        </div>
                    </div>
                </div>
                <a href="#" class="font-medium text-gray-600 hover:text-primary-gold transition-colors pb-1 border-b-2 border-transparent">Virtual Try-On</a>
                <a href="#" class="font-medium text-gray-600 hover:text-primary-gold transition-colors pb-1 border-b-2 border-transparent">Events</a>
                <a href="#" class="font-medium text-gray-600 hover:text-primary-gold transition-colors pb-1 border-b-2 border-transparent">About</a>
            </nav>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-primary-gold hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-gold" aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen.toString()">
                    <span class="sr-only">Open main menu</span>
                    <svg x-show="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on mobileMenuOpen state. -->
    <div class="md:hidden" id="mobile-menu" x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="mobileMenuOpen = false"> {{-- Closes menu when clicking outside --}}
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'bg-primary-gold text-white' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-gold' }}">Home</a>
            <a href="{{ route('blog.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('blog.index*') ? 'bg-primary-gold text-white' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-gold' }}">Blog</a>
            {{-- Add other mobile links here --}}
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-gold">Virtual Try-On</a>
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-gold">Events</a>
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-gold">About</a>
        </div>
    </div>
</header>
@else
<header class="bg-white/80 sticky top-0 z-50">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="#" class="font-bold text-lg">LatestFashionJewellery</a>
            <nav class="space-x-4">
                <a href="#" class="text-gray-700">Home</a>
                <a href="#" class="text-gray-700">Blog</a>
            </nav>
        </div>
    </div>
</header>
@endif
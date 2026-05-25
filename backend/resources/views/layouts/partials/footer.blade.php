@if (!app()->environment('testing'))
<footer class="bg-dark-slate text-soft-cream py-16">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
            <div class="md:col-span-1 text-center md:text-left">
                 <a href="{{ route('home') }}" title="Latest Fashion Jewellery Homepage">
                    <span class="text-2xl font-playfair font-bold text-primary-gold hover:text-opacity-80 transition-colors">
                        LatestFashionJewellery
                    </span>
                </a>
                <p class="mt-2 text-sm text-gray-400">Your guide to the most exquisite jewellery trends and styles.</p>
            </div>

            <nav class="md:col-span-1 flex flex-col md:items-center space-y-2 text-center">
                <h3 class="font-semibold text-primary-gold uppercase tracking-wider text-sm">Quick Links</h3>
                <a href="{{ route('blog.index') }}" class="hover:text-accent-rose transition-colors">Blog</a>
                <a href="#" class="hover:text-accent-rose transition-colors">Virtual Try-On</a>
                <a href="#" class="hover:text-accent-rose transition-colors">About Us</a>
                <a href="#" class="hover:text-accent-rose transition-colors">Contact</a>
            </nav>

            <div class="md:col-span-1 text-center md:text-right">
                <h3 class="font-semibold text-primary-gold uppercase tracking-wider text-sm">Legal</h3>
                <a href="#" class="block hover:text-accent-rose transition-colors">Privacy Policy</a>
                <a href="#" class="block hover:text-accent-rose transition-colors">Terms of Service</a>
                <a href="#" class="block hover:text-accent-rose transition-colors">Affiliate Disclosure</a>
                {{-- Social media icons --}}
                <div class="mt-4 flex justify-center md:justify-end space-x-4">
                    <a href="#" class="text-gray-400 hover:text-primary-gold"><span class="sr-only">Facebook</span><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">...</svg></a>
                    <a href="#" class="text-gray-400 hover:text-primary-gold"><span class="sr-only">Instagram</span><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">...</svg></a>
                    <a href="#" class="text-gray-400 hover:text-primary-gold"><span class="sr-only">Pinterest</span><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">...</svg></a>
                </div>
            </div>
        </div>
        <div class="mt-12 border-t border-gray-700 pt-8 text-center">
            <p class="text-sm text-gray-400">© {{ date('Y') }} LatestFashionJewellery.com. All rights reserved.</p>
            <p class="text-xs text-gray-500 mt-1">This site is a participant in the Amazon Services LLC Associates Program, an affiliate advertising program designed to provide a means for sites to earn advertising fees by advertising and linking to Amazon.com.</p>
        </div>
    </div>
</footer>
@else
<footer class="bg-gray-100 text-gray-700 py-8">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="font-bold">LatestFashionJewellery</p>
        <p class="text-sm text-gray-500">© {{ date('Y') }} LatestFashionJewellery.com</p>
    </div>
</footer>
@endif
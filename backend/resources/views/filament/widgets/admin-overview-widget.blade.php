<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Admin Overview</x-slot>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->getCounts() as $item)
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item['label'] }}</div>
                    <div class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $item['count'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            @foreach ($this->getQuickLinks() as $link)
                <x-filament::button tag="a" href="{{ $link['href'] }}" color="{{ $link['color'] }}" size="sm">
                    {{ $link['label'] }}
                </x-filament::button>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Top Action Items</x-slot>

        <div class="space-y-3">
            @foreach ($this->getActionItems() as $item)
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:border-primary hover:shadow-md dark:border-gray-700 dark:bg-slate-900">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $item['label'] }}</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $item['count'] }}</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                            {{ $item['badge'] }}
                        </span>
                    </div>
                    <div class="mt-4">
                        <x-filament::button tag="a" href="{{ $item['href'] }}" size="sm" color="secondary">
                            Open resource
                        </x-filament::button>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

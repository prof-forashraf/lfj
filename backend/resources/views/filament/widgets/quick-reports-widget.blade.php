<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Quick Reports</x-slot>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($this->getReports() as $report)
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $report['label'] }}</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $report['count'] }}</p>
                        </div>
                        <x-filament::badge :color="$report['color']">{{ $report['count'] > 0 ? 'Action' : 'OK' }}</x-filament::badge>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
            <div class="mb-3 text-sm font-semibold text-slate-700 dark:text-slate-300">Daily update quick links</div>
            <div class="flex flex-wrap gap-3">
                @foreach ($this->getDailyUpdateLinks() as $link)
                    <x-filament::button tag="a" href="{{ $link['href'] }}" color="{{ $link['color'] }}" size="sm">
                        {{ $link['label'] }}
                    </x-filament::button>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

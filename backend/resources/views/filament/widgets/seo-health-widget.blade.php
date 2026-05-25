<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">SEO Health</x-slot>

        <div class="space-y-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 font-semibold">Metric</th>
                            <th class="py-2 font-semibold">Count</th>
                            <th class="py-2 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->getStats() as $row)
                            @php
                                $color = $row['count'] === 0 ? 'success' : ($row['count'] <= 5 ? 'warning' : 'danger');
                            @endphp
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="py-2">{{ $row['metric'] }}</td>
                                <td class="py-2">{{ $row['count'] }}</td>
                                <td class="py-2">
                                    <x-filament::badge :color="$color">{{ $row['status'] }}</x-filament::badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-sm text-slate-600 dark:text-slate-300">
                These SEO health metrics are sourced directly from the database.
            </div>

            <div class="flex flex-wrap gap-3">
                <x-filament::button wire:click="fixMissingSeoTitles" color="warning">Fix Missing SEO Titles</x-filament::button>
                <x-filament::button wire:click="regenerateSitemap" color="primary">Regenerate Sitemap</x-filament::button>
                <x-filament::button tag="a" href="{{ url('/admin/resources/posts') }}" color="secondary">Review Posts</x-filament::button>
                <x-filament::button tag="a" href="{{ url('/admin/resources/events') }}" color="secondary">Review Events</x-filament::button>
                <x-filament::button tag="a" href="{{ url('/admin/resources/categories') }}" color="secondary">Review Categories</x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

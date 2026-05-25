<x-filament::page>
    <div class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ($this->latestMarketMetrics['live'] ?? [] as $symbol => $data)
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">{{ $symbol }}</p>
                        <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">USD</span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">
                        {{ $data['price_per_unit'] !== null ? '$' . number_format($data['price_per_unit'], 2) : 'No data' }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">Last updated: {{ $data['timestamp'] ? \\Carbon\\Carbon::parse($data['timestamp'])->diffForHumans() : 'unknown' }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Sync health</h2>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    <div class="flex justify-between gap-4">
                        <span>Last sync status</span>
                        <span>{{ $this->latestMarketMetrics['health']['last_sync_status'] ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span>Last sync time</span>
                        <span>{{ $this->latestMarketMetrics['health']['last_sync_at'] ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span>Consecutive failures</span>
                        <span>{{ $this->latestMarketMetrics['health']['failure_count'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span>Circuit breaker</span>
                        <span>{{ $this->latestMarketMetrics['circuit_breaker']['status'] ?? 'Unknown' }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Manual actions</h2>
                <p class="mt-4 text-sm text-slate-600">
                    Queue a sync, reset the API circuit breaker, or manually refresh in-memory market data.
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Raw daily metal price history</h2>
            <p class="mt-2 text-sm text-slate-500">Edit source and notes in place. These values are used for tracking market history and correcting stale API records.</p>
            <div class="mt-6">{{ $this->table }}</div>
        </div>
    </div>
</x-filament::page>

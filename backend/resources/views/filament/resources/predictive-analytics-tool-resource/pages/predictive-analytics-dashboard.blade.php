<x-filament-panels::page
    wire:poll.30s="refreshData"
    x-data="{
        forecast: @entangle('forecast').live,
        chartData: null,
        init() {
            this.refreshData();
        },
        refreshData() {
            fetch('/admin/predictive-analytics/forecast-data')
                .then(response => response.json())
                .then(data => {
                    this.chartData = data;
                    this.renderChart();
                });
        },
        renderChart() {
            if (!this.chartData) return;

            const ctx = document.getElementById('forecastChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.chartData.labels,
                    datasets: [{
                        label: 'Predicted Sales',
                        data: this.chartData.predicted,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Historical Sales',
                        data: this.chartData.historical,
                        borderColor: 'rgb(156, 163, 175)',
                        backgroundColor: 'rgba(156, 163, 175, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Sales Forecast Analysis'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    }"
>
    <div class="space-y-6">
        <!-- Forecast Configuration -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Forecast Configuration
                </h3>

                {{ $this->form }}
            </div>
        </div>

        <!-- Forecast Results -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-arrow-trending-up class="h-8 w-8 text-green-500" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Next Month Prediction
                                </dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ Cache::get('predictive_forecast.next_month', '$0.00') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-chart-bar class="h-8 w-8 text-blue-500" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Growth Rate
                                </dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ Cache::get('predictive_forecast.growth_rate', '0%') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-cursor-arrow-rays class="h-8 w-8 text-purple-500" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Confidence Level
                                </dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ Cache::get('predictive_forecast.confidence', '85%') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forecast Chart -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Sales Forecast Chart
                </h3>
                <canvas id="forecastChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Insights and Recommendations -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    AI Insights & Recommendations
                </h3>
                <div class="space-y-4">
                    @foreach(Cache::get('predictive_forecast.insights', []) as $insight)
                        <div class="border-l-4 border-blue-400 bg-blue-50 dark:bg-blue-900/20 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <x-heroicon-o-information-circle class="h-5 w-5 text-blue-400" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        {{ $insight }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
</x-filament-panels::page>
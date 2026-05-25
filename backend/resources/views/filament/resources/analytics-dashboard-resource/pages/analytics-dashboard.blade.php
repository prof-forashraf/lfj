<x-filament-panels::page
    wire:poll.30s="refreshData"
    x-data="{
        activeTab: 'overview',
        chartInstances: {},
        init() {
            this.$watch('activeTab', () => this.refreshCharts());
            this.refreshCharts();
        },
        refreshCharts() {
            setTimeout(() => {
                this.updateCharts();
            }, 100);
        },
        updateCharts() {
            // Chart.js integration would go here
            console.log('Charts refreshed');
        }
    }"
>
    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{ $this->form }}
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" x-show="activeTab === 'overview' || activeTab === ''">
            <!-- Total Users -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <x-heroicon-o-users class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($this->getDashboardData()['summary']['total_users'] ?? 0) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <x-heroicon-o-cube class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($this->getDashboardData()['summary']['total_products'] ?? 0) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Categories -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <x-heroicon-o-tag class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Categories</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($this->getDashboardData()['summary']['total_categories'] ?? 0) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Featured Products -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <x-heroicon-o-star class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Featured</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($this->getDashboardData()['summary']['featured_products'] ?? 0) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics (when performance tab is selected) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-show="activeTab === 'performance'">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Response Time</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($this->getDashboardData()['response_time'] ?? 0, 1) }}ms
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Cache Hit Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format(($this->getDashboardData()['cache_hit_rate'] ?? 0) * 100, 1) }}%
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Error Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format(($this->getDashboardData()['error_rate'] ?? 0) * 100, 2) }}%
                    </p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- User Growth Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" x-show="activeTab === 'overview' || activeTab === 'users'">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Growth Trend</h3>
                <canvas id="userGrowthChart" width="400" height="200"></canvas>
            </div>

            <!-- Product Views Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" x-show="activeTab === 'overview' || activeTab === 'products'">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Product Views (7 Days)</h3>
                <canvas id="productViewsChart" width="400" height="200"></canvas>
            </div>

            <!-- Category Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" x-show="activeTab === 'overview' || activeTab === 'products'">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Category Distribution</h3>
                <canvas id="categoryChart" width="400" height="200"></canvas>
            </div>

            <!-- Price Distribution -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" x-show="activeTab === 'products'">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Price Distribution</h3>
                <canvas id="priceChart" width="400" height="200"></canvas>
            </div>

            <!-- Response Time Trend -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" x-show="activeTab === 'performance'">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Response Time Trend</h3>
                <canvas id="responseTimeChart" width="400" height="200"></canvas>
            </div>

            <!-- Error Rate Trend -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" x-show="activeTab === 'performance'">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Error Rate Trend</h3>
                <canvas id="errorRateChart" width="400" height="200"></canvas>
            </div>

            <!-- Revenue Trend -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" x-show="activeTab === 'revenue'">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Revenue Trend</h3>
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>

            <!-- Conversion Funnel -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" x-show="activeTab === 'revenue'">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Conversion Funnel</h3>
                <canvas id="conversionChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Data Tables -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Products Table -->
            <div x-show="activeTab === 'products'">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Popular Products</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($this->getDashboardData()['popular_products'] ?? [] as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $product['product_name_snapshot'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    ${{ number_format($product['price'] ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ ($product['is_featured'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ($product['is_featured'] ?? false) ? 'Featured' : 'Regular' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Categories Table -->
            <div x-show="activeTab === 'products'">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Category Performance</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Products</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($this->getDashboardData()['category_performance'] ?? [] as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $category['name'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $category['product_count'] ?? 0 }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:loaded', () => {
            // Initialize charts when the page loads
            initCharts();
        });

        function initCharts() {
            // User Growth Chart
            const userGrowthCtx = document.getElementById('userGrowthChart');
            if (userGrowthCtx) {
                new Chart(userGrowthCtx, {
                    type: 'line',
                    data: @json($this->getDashboardData()['charts']['user_growth'] ?? []),
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true }
                        }
                    }
                });
            }

            // Product Views Chart
            const productViewsCtx = document.getElementById('productViewsChart');
            if (productViewsCtx) {
                new Chart(productViewsCtx, {
                    type: 'line',
                    data: @json($this->getDashboardData()['charts']['product_views'] ?? []),
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true }
                        }
                    }
                });
            }

            // Category Distribution Chart
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx) {
                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: @json($this->getDashboardData()['charts']['category_distribution'] ?? []),
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true }
                        }
                    }
                });
            }

            // Price Distribution Chart
            const priceCtx = document.getElementById('priceChart');
            if (priceCtx) {
                new Chart(priceCtx, {
                    type: 'bar',
                    data: @json($this->getDashboardData()['charts']['price_distribution'] ?? []),
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true }
                        }
                    }
                });
            }

            // Response Time Chart
            const responseTimeCtx = document.getElementById('responseTimeChart');
            if (responseTimeCtx) {
                new Chart(responseTimeCtx, {
                    type: 'line',
                    data: @json($this->getDashboardData()['charts']['response_time_trend'] ?? []),
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true }
                        }
                    }
                });
            }

            // Error Rate Chart
            const errorRateCtx = document.getElementById('errorRateChart');
            if (errorRateCtx) {
                new Chart(errorRateCtx, {
                    type: 'line',
                    data: @json($this->getDashboardData()['charts']['error_rate_trend'] ?? []),
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true }
                        }
                    }
                });
            }

            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                new Chart(revenueCtx, {
                    type: 'line',
                    data: @json($this->getDashboardData()['charts']['revenue_trend'] ?? []),
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true }
                        }
                    }
                });
            }

            // Conversion Chart
            const conversionCtx = document.getElementById('conversionChart');
            if (conversionCtx) {
                new Chart(conversionCtx, {
                    type: 'bar',
                    data: @json($this->getDashboardData()['charts']['conversion_funnel'] ?? []),
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true }
                        }
                    }
                });
            }
        }

        // Reinitialize charts when data refreshes
        document.addEventListener('refresh-dashboard', () => {
            setTimeout(() => {
                initCharts();
            }, 500);
        });
    </script>
</x-filament-panels::page>
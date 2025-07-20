<x-filament::page>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 mb-6">
        {{-- Kapasitas Air --}}
        <div
            class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex items-center space-x-4 border-l-4 border-blue-500 dark:border-blue-400">
            <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3.25C12 3.25 7 9.5 7 13.25C7 16.5 9.5 19 12 19C14.5 19 17 16.5 17 13.25C17 9.5 12 3.25 12 3.25Z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500 dark:text-gray-300">Kapasitas Air (Liter)</div>
                <div class="text-xl font-bold text-gray-800 dark:text-white">{{ number_format($kapasitasAir) }} L</div>
            </div>
        </div>

        {{-- Stok Gas --}}
        <div
            class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex items-center space-x-4 border-l-4 border-green-500 dark:border-green-400">
            <div class="bg-green-100 dark:bg-green-900 p-2 rounded-full">
                <x-heroicon-o-fire class="w-6 h-6 text-green-600 dark:text-green-300" />
            </div>
            <div>
                <div class="text-sm text-gray-500 dark:text-gray-300">Stok Gas Tabung</div>
                <div class="text-xl font-bold text-gray-800 dark:text-white">
                    {{ Number::format($stokGasTabung, precision: 0) }}
                </div>
            </div>
        </div>

        {{-- Pendapatan --}}
        <div
            class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex items-center space-x-4 border-l-4 border-yellow-500 dark:border-yellow-400">
            <div class="bg-yellow-100 dark:bg-yellow-900 p-2 rounded-full">
                <x-heroicon-o-currency-dollar class="w-6 h-6 text-yellow-600 dark:text-yellow-300" />
            </div>
            <div>
                <div class="text-sm text-gray-500 dark:text-gray-300">Total Pendapatan</div>
                <div class="text-xl font-bold text-gray-800 dark:text-white">
                    Rp {{ number_format($totalPendapatan) }}
                </div>
            </div>
        </div>

        {{-- Total Transaksi --}}
        <div
            class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex items-center space-x-4 border-l-4 border-red-500 dark:border-red-400">
            <div class="bg-red-100 dark:bg-red-900 p-2 rounded-full">
                <x-heroicon-o-shopping-cart class="w-6 h-6 text-red-600 dark:text-red-300" />
            </div>
            <div>
                <div class="text-sm text-gray-500 dark:text-gray-300">Total Transaksi</div>
                <div class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalTransaksi }}</div>
            </div>
        </div>
    </div>

    {{-- Chart Penjualan --}}
    <x-filament::card class="dark:bg-gray-800 dark:text-white">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-700 dark:text-white">Statistik Penjualan</h2>
            <form method="GET">
                <select name="filter" onchange="this.form.submit()"
                    class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    <option value="daily" {{ request('filter') === 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="monthly" {{ request('filter') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ request('filter') === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </form>
        </div>

        <canvas id="salesChart" height="100"></canvas>
    </x-filament::card>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('salesChart');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Total Penjualan',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: '#3b82f6',
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat().format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                autoSkip: false,
                                maxRotation: 90,
                                minRotation: 45,
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Penjualan (Rp)'
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-filament::page>

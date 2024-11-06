<x-layout>
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-extrabold text-gray-800">Laporan Stok Produk</h2>
            <a href="{{ route('reports.export') }}"
                class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-semibold rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Excel
            </a>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg sm:rounded-xl p-6">
                <!-- Chart Section -->
                <div class="bg-gray-100 shadow-lg sm:rounded-xl mb-8 p-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Grafik Pergerakan Stok</h3>
                    <div class="h-96">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="bg-white shadow-lg sm:rounded-xl p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Laporan Stok Produk</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Nama Produk</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Stok Awal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Stok Saat Ini</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Perubahan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wide">
                                        Riwayat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($products as $product)
                                    <tr class="hover:bg-gray-100 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($product->initial_stock) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($product->current_stock) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $stockDiff = $product->current_stock - $product->initial_stock;
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs font-semibold rounded-full
                                                {{ $stockDiff > 0 ? 'bg-green-200 text-green-800' : ($stockDiff < 0 ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800') }}">
                                                {{ $stockDiff > 0 ? '+' : '' }}{{ number_format($stockDiff) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:text-blue-800 cursor-pointer"
                                            onclick="toggleHistory('history-{{ $product->id }}')">
                                            Lihat Riwayat
                                        </td>
                                    </tr>

                                    <!-- History Row -->
                                    <tr id="history-{{ $product->id }}" class="hidden">
                                        <td colspan="5" class="px-6 py-4 bg-gray-50 rounded-lg">
                                            <div class="p-4 border border-gray-200 rounded-lg shadow-sm">
                                                <h4 class="font-semibold text-gray-700 mb-2">Riwayat Perubahan</h4>
                                                @forelse($product->productHistories as $history)
                                                    <div
                                                        class="mb-2 p-3 bg-white rounded-lg shadow-sm flex justify-between items-center">
                                                        <div>
                                                            <span
                                                                class="text-sm {{ $history->old_value > $history->new_value ? 'text-red-600' : 'text-green-600' }}">
                                                                {{ $history->old_value }} → {{ $history->new_value }}
                                                            </span>
                                                            <span class="text-gray-600 text-sm ml-2">
                                                                ({{ $history->reason_changed }})
                                                            </span>
                                                        </div>
                                                        <span class="text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($history->created_at)->format('d M Y H:i') }}
                                                        </span>
                                                    </div>
                                                @empty
                                                    <p class="text-gray-500 text-sm">Belum ada riwayat perubahan</p>
                                                @endforelse
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript and Chart.js scripts -->
        <script>
            function toggleHistory(historyId) {
                const historyRow = document.getElementById(historyId);
                historyRow.classList.toggle('hidden');
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('stockChart').getContext('2d');
                const chartData = @json($chartData);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData[0].dates,
                        datasets: chartData.map(product => ({
                            label: product.name,
                            data: product.data,
                            fill: false,
                            tension: 0.1,
                            borderWidth: 2
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            title: {
                                display: true,
                                text: 'Pergerakan Stok'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Stok'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Tanggal'
                                }
                            }
                        }
                    }
                });
            });
        </script>
</x-layout>

<x-layout>
    {{-- AI GENERATED --}}
    <div class="container mx-auto px-4 py-6">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl text-zinc-900 font-bold text-center mb-2">Selamat datang kembali,
                {{ auth()->user()->name }}</h1>
            <p class="text-center text-zinc-600">Berikut adalah ringkasan untuk hari ini</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Total Products Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-600">Total Produk</p>
                        <h3 class="text-2xl font-bold text-zinc-900">{{ $products->count() }}</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Stock Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-600">Total Stok</p>
                        <h3 class="text-2xl font-bold text-zinc-900">{{ number_format($totalStock) }}</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-600">Item Stok Terendah</p>
                        <h3 class="text-xl font-bold text-zinc-900">{{ $minStock->name }}</h3>
                        <p class="text-sm text-red-600">Hanya {{ $minStock->current_stock }} unit tersisa</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-600">Pendapatan Kotor Bulanan</p>
                        <!-- Menampilkan total pendapatan dengan format Rupiah -->
                        <h3 class="text-2xl font-bold text-zinc-900">
                            Rp 
                            @if($totalPendapatan >= 1000000000)
                                {{ number_format($totalPendapatan / 1000000000, 2, ',', '.') }} Miliar
                            @elseif($totalPendapatan >= 1000000)
                                {{ number_format($totalPendapatan / 1000000, 2, ',', '.') }} Juta
                            @else
                                {{ number_format($totalPendapatan, 0, ',', '.') }}
                            @endif
                        </h3>
                        <!-- Menampilkan persentase perubahan -->
                        @if(is_numeric($persentasePerubahan))
                            <span class="text-sm {{ $persentasePerubahan >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ↑ {{ number_format($persentasePerubahan, 2, ',', '.') }}%
                            </span>
                        @else
                            <span class="text-sm text-gray-600">Tidak terdeteksi</span>
                        @endif
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6">
                <h2 class="text-xl font-bold text-zinc-900 mb-4">Produk Terlaris (30 Hari Terakhir)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200">
                        <thead>
                            <tr class="bg-zinc-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Produk Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Terjual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Performa</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-zinc-200">
                            @foreach ($topProducts as $index => $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-zinc-900">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-zinc-900">{{ number_format($product->total_sold) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-zinc-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($product->total_sold / $topProducts->max('total_sold') * 100), 100) }}%"></div>
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
</x-layout>

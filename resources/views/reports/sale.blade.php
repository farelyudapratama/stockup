<x-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Laporan Penjualan</h1>
            <p class="text-gray-600 mt-1">Periode: {{ request('start_date', 'Semua') }} -
                {{ request('end_date', 'Semua') }}</p>
        </div>

        <form method="GET" id="filter" action="{{ route('reports.sale') }}" class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">

                <!-- Start Date Filter -->
                <div class="space-y-2">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Dari</label>
                    <input type="date" name="start_date" id="start_date"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        value="{{ request('start_date') }}">
                </div>

                <!-- End Date Filter -->
                <div class="space-y-2">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Sampai</label>
                    <input type="date" name="end_date" id="end_date"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        value="{{ request('end_date') }}">
                </div>

                <!-- Product Filter -->
                <div class="space-y-2">
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Produk</label>
                    <select name="product_id" id="product_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <!-- Apply Filter -->
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Terapkan Filter
                </button>

                <!-- Reset Filter -->
                <a href="{{ route('reports.sale') }}"
                    class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-2 rounded-md">
                    Reset Filter
                </a>

                <!-- Export Reports Sale to Excel -->
                <a href="{{ route('reports.saleexport', request()->all()) }}"
                    class="ml-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Excel
                </a>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Total Transaksi Penjualan</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalTransactions }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Total Barang Terjual</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalUnitsSold }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Total Nilai Penjualan</h3>
                <p class="text-2xl font-semibold text-gray-900">
                    Rp{{ number_format($totalAmount, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            ID Barang
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            Nama Barang
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            Jumlah Barang Terjual
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            Harga Jual Satuan (IDR)
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            Total Penjualan (IDR)
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($sales as $sale)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 border border-gray-300">
                                {{ $sale['product_id'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 border border-gray-300">
                                {{ $sale['product_name'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 border border-gray-300">{{ $sale['quantity'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right border border-gray-300">
                                {{ number_format($sale['unit_price'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right border border-gray-300">
                                {{ number_format($sale['total'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach

                    @if ($sales->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 border border-gray-200">
                            Tidak ada data penjualan
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layout>

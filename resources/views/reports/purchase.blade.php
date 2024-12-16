<x-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Laporan Pembelian</h1>
            <p class="text-gray-600 mt-1">Periode: {{ request('start_date', 'Semua') }} -
                {{ request('end_date', 'Semua') }}</p>
        </div>

        <!-- Filter Section -->
        <form method="GET" id="filter" action="{{ route('reports.purchase') }}" class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <!-- Vendor Filter -->
                <div class="space-y-2">
                    <label for="vendor_id" class="block text-sm font-medium text-gray-700">Vendor</label>
                    <select name="vendor_id" id="vendor_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Vendor</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}"
                                {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Terapkan Filter
                </button>
                <a href="{{ route('reports.purchase') }}"
                    class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-2 rounded-md">
                    Reset Filter
                </a>
                <a href="{{ route('reports.purchaseexport', request()->query()) }}"
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

        <!-- Ringkasan Data -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Total Pembelian</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalPurchases }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Total Nilai Pembelian</h3>
                <p class="text-2xl font-semibold text-gray-900">
                    Rp{{ number_format($totalAmount, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Jumlah Vendor</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ $totalVendors }}</p>
            </div>
        </div>

        <!-- Tabel Data Pembelian -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            Tanggal Pembelian
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            Nama Barang
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            Jumlah
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            Harga Satuan (IDR)
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            Sub Total (IDR)
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase border border-gray-300">
                            Total keseluruhan
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($purchases->groupBy('vendor_id') as $vendorGroup)
                        <!-- Vendor Header -->
                        <tr class="bg-gray-100">
                            <td colspan="6" class="px-6 py-3 font-medium border border-gray-200">
                                Pemasok: {{ $vendorGroup->first()->vendor->name }}
                            </td>
                        </tr>

                        @foreach ($vendorGroup as $purchase)
                            @php
                                $purchaseDetails = $purchase->details;
                                $totalRows = count($purchaseDetails);
                                $purchaseTotal = $purchaseDetails->sum(function ($detail) {
                                    return $detail->quantity * $detail->price;
                                });
                            @endphp

                            @foreach ($purchaseDetails as $index => $detail)
                                <tr class="hover:bg-gray-200">
                                    <td class="px-6 py-4 text-sm text-gray-900 border border-gray-300">
                                        {{ $purchase->purchase_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 border border-gray-300">
                                        {{ $detail->product->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 border border-gray-300">
                                        {{ number_format($detail->quantity, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-right border border-gray-300">
                                        {{ number_format($detail->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-right border border-gray-300">
                                        {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                    @if ($index === 0)
                                        <td class="px-6 py-4 text-sm text-gray-900 text-right border border-gray-300"
                                            rowspan="{{ $totalRows }}">
                                            {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach

                    @if ($purchases->isEmpty())
                        <tr>
                            <td colspan="6"
                                class="px-6 py-4 text-center text-sm text-gray-500 border border-gray-200">
                                Tidak ada data pembelian
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="px-4 py-4 bg-gray-100 border-t">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                {{-- Dropdown untuk memilih jumlah entri --}}
                <div class="flex items-center gap-4">
                    <select name="entries" id="entries"
                        onchange="window.location.href = '{{ request()->fullUrlWithQuery(['entries' => '']) }}' + this.value"
                        class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="10" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries', 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('entries', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-gray-600">entri per halaman</span>
                </div>

                {{-- Informasi jumlah data --}}
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $purchases->firstItem() ?? 0 }} hingga {{ $purchases->lastItem() ?? 0 }} dari
                    {{ $purchases->total() }} entri
                    @if (request('search'))
                        <span class="block sm:inline sm:ml-2">
                            (difilter dari {{ $purchases->total() }} total entri)
                        </span>
                    @endif
                </div>

                {{-- Tombol navigasi --}}
                <div class="flex gap-2">
                    @if ($purchases->onFirstPage())
                        <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i> Previous
                        </span>
                    @else
                        <a href="{{ $purchases->appends([
                                'entries' => request('entries', 10),
                                'vendor_id' => request('vendor_id'),
                                'start_date' => request('start_date'),
                                'end_date' => request('end_date'),
                                'product_id' => request('product_id'),
                            ])->previousPageUrl() }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    @endif

                    @if ($purchases->hasMorePages())
                        <a href="{{ $purchases->appends([
                                'entries' => request('entries', 10),
                                'vendor_id' => request('vendor_id'),
                                'start_date' => request('start_date'),
                                'end_date' => request('end_date'),
                                'product_id' => request('product_id'),
                            ])->nextPageUrl() }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                            Next <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>

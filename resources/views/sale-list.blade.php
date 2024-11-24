<x-layout>
    <x-slot:folderName>Master Data</x-slot:folderName>

    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-center bg-gray-100 p-4 border-b">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-700 mb-3 sm:mb-0">Daftar Penjualan</h2>
                {{-- <a href="{{ route('sales.create') }}"
                    class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-semibold rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg text-center">
                    + Tambah Penjualan
                </a> --}}
            </div>

            <!-- Filter Section -->
            <div class="p-4 border-b bg-gray-50">
                <form method="GET" action="{{ route('sales.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Search Filter -->
                        <div class="space-y-2">
                            <label for="search" class="block text-sm font-medium text-gray-700">Cari ID atau
                                Pembeli</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm"
                                placeholder="Cari ID atau Pembeli...">
                        </div>

                        <!-- Entries per page -->
                        <div class="space-y-2">
                            <label for="entries" class="block text-sm font-medium text-gray-700">Tampilkan</label>
                            <select name="entries" id="entries"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm"
                                onchange="this.form.submit()">
                                @foreach ([10, 20, 50] as $entry)
                                    <option value="{{ $entry }}"
                                        {{ request('entries', 10) == $entry ? 'selected' : '' }}>
                                        {{ $entry }} entries
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('sales.index') }}"
                            class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition duration-200 text-center">
                            Reset Filter
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <div class="min-w-full divide-y divide-gray-200">
                    <!-- Table Header -->
                    <div class="hidden sm:grid sm:grid-cols-5 bg-gray-50">
                        <div class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase">ID</div>
                        <div class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama Pembeli
                        </div>
                        <div class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal Penjualan
                        </div>
                        <div class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase">Total Amount
                        </div>
                        <div class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase">Detail</div>
                    </div>

                    <!-- Table Body -->
                    <div class="divide-y divide-gray-200">
                        @forelse ($sales as $index => $sale)
                            <!-- Mobile Card View -->
                            <div class="sm:hidden p-4 space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium">ID:</span>
                                    <span>{{ $sale->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Nama Pembeli:</span>
                                    <span>{{ $sale->buyer_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Tanggal:</span>
                                    <span>{{ $sale->sale_date->format('d-m-Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Total:</span>
                                    <span>Rp{{ number_format($sale->total_amount, 2, ',', '.') }}</span>
                                </div>
                                {{-- <div class="flex justify-end gap-2 pt-2">
                                    <a href="{{ route('sales.show', $sale->id) }}"
                                        class="px-3 py-1 text-blue-600 hover:text-blue-900">Lihat Detail</a>
                                </div> --}}
                            </div>

                            <!-- Desktop/Tablet Table View -->
                            <div class="hidden sm:grid sm:grid-cols-5 hover:bg-gray-300">
                                <div class="py-3 px-4">{{ $sale->id }}</div>
                                <div class="py-3 px-4">{{ $sale->buyer_name }}</div>
                                <div class="py-3 px-4">{{ $sale->sale_date->format('d-m-Y') }}</div>
                                <div class="py-3 px-4">Rp{{ number_format($sale->total_amount, 2, ',', '.') }}</div>
                                {{-- <div class="py-3 px-4">
                                    <a href="{{ route('sales.show', $sale->id) }}"
                                        class="text-blue-600 hover:text-blue-900">Lihat Detail</a>
                                </div> --}}
                            </div>
                        @empty
                            <div class="py-8 px-4 text-center text-gray-500">
                                Tidak ada penjualan yang ditemukan
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Pagination Section -->
            <div class="px-4 py-4 bg-gray-100 border-t">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600 text-center sm:text-left">
                        Menampilkan {{ $sales->firstItem() ?? 0 }} hingga {{ $sales->lastItem() ?? 0 }} dari
                        {{ $sales->total() }} entri
                        @if (request()->hasAny(['search', 'entries']))
                            <span class="block sm:inline sm:ml-2">(difilter dari {{ $sales->total() }} total
                                entri)</span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @if ($sales->onFirstPage())
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed"><i
                                    class="fas fa-chevron-left"></i> Previous</span>
                        @else
                            <a href="{{ $sales->appends(request()->query())->previousPageUrl() }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"><i
                                    class="fas fa-chevron-left"></i> Previous</a>
                        @endif

                        @if ($sales->hasMorePages())
                            <a href="{{ $sales->appends(request()->query())->nextPageUrl() }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">Next
                                <i class="fas fa-chevron-right"></i></a>
                        @else
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">Next <i
                                    class="fas fa-chevron-right"></i></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>

<x-layout>
    <x-slot:folderName>Master Data</x-slot:folderName>

    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-center bg-gray-100 p-4 border-b">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-700 mb-3 sm:mb-0">Daftar Penjualan</h2>
                <a href="{{ route('sales.create') }}"
                    class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-semibold rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg text-center">
                    + Tambah Penjualan
                </a>
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

                        <div class="space-y-2">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                    
                        <div class="space-y-2">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
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
                <!-- Desktop Table View -->
                <table class="hidden sm:table min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-11">
                                No
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-24">
                                ID
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-48">
                                Nama Pembeli
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-32">
                                Tanggal
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-40">
                                Total Amount
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-24">
                                Detail
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-32">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($sales as $index => $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-center text-sm text-gray-900 whitespace-nowrap border-r">
                                    {{ $index + $sales->firstItem() }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900 whitespace-nowrap border-r">
                                    {{ $sale->id }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900 border-r">
                                    <span class="truncate block max-w-xs" title="{{ $sale->buyer_name }}">
                                        {{ Str::limit($sale->buyer_name, 20) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900 whitespace-nowrap border-r">
                                    {{ $sale->sale_date->format('d-m-Y') }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900 whitespace-nowrap border-r">
                                    Rp{{ number_format($sale->total_amount, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center border-r">
                                    <a href="{{ route('sales.detail', $sale->id) }}"
                                        class="inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 transition-colors duration-200">
                                        Lihat Detail
                                    </a>
                                </td>
                                <td class="py-3 text-center border-r">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('sales.edit', $sale->id) }}"
                                            class="inline-flex items-center justify-center px-7 py-1 bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200 transition-colors duration-200">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center px-7 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors duration-200">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada penjualan yang ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Card View -->
                <div class="sm:hidden divide-y divide-gray-200">
                    @forelse ($sales as $index => $sale)
                        <div class="p-4 bg-white hover:bg-gray-50">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">No:</span>
                                    <span class="text-sm text-gray-900">{{ $index + $sales->firstItem() }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">ID:</span>
                                    <span class="text-sm text-gray-900">{{ $sale->id }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Pembeli:</span>
                                    <span class="text-sm text-gray-900 truncate block max-w-xs" title="{{ $sale->buyer_name }}">
                                        {{ Str::limit($sale->buyer_name, 20) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Tanggal:</span>
                                    <span class="text-sm text-gray-900">{{ $sale->sale_date->format('d-m-Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Total:</span>
                                    <span
                                        class="text-sm text-gray-900">Rp{{ number_format($sale->total_amount, 2, ',', '.') }}</span>
                                </div>

                                <!-- Mobile Actions -->
                                <div class="mt-4 flex flex-col space-y-2">
                                    <a href="{{ route('sales.detail', $sale->id) }}"
                                        class="w-full py-2 bg-blue-100 text-blue-600 rounded-md text-center text-sm font-medium hover:bg-blue-200 transition-colors duration-200">
                                        Lihat Detail
                                    </a>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('sales.edit', $sale->id) }}"
                                            class="py-2 bg-yellow-100 text-yellow-800 rounded-md text-center text-sm font-medium hover:bg-yellow-200 transition-colors duration-200">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full py-2 bg-red-100 text-red-600 rounded-md text-sm font-medium hover:bg-red-200 transition-colors duration-200">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            Tidak ada penjualan yang ditemukan
                        </div>
                    @endforelse
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
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i> Previous
                            </span>
                        @else
                            <a href="{{ $sales->appends(request()->query())->previousPageUrl() }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        @endif

                        @if ($sales->hasMorePages())
                            <a href="{{ $sales->appends(request()->query())->nextPageUrl() }}"
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
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            endDateInput.addEventListener('change', function() {
                if (this.value) {
                    startDateInput.setAttribute('max', this.value);
                } else {
                    startDateInput.removeAttribute('max');
                }
            });

            startDateInput.addEventListener('change', function() {
                if (this.value) {
                    endDateInput.setAttribute('min', this.value);
                } else {
                    endDateInput.removeAttribute('min');
                }
            });
        });
        @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: '{{ session('error_type') ?? 'error' }}',
                    title: '{{ session('error_title') ?? 'Terjadi Kesalahan' }}',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif
    </script>
</x-layout>

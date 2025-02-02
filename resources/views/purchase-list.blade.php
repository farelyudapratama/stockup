<x-layout>
    <x-slot:folderName>Master Data</x-slot:folderName>

    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-center bg-gray-100 p-4 border-b">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-700 mb-3 sm:mb-0">Daftar Pembelian</h2>
                <a href="{{ route('purchases.create') }}"
                    class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-semibold rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg text-center">
                    + Tambah Pembelian
                </a>
            </div>

            <!-- Filter Section -->
            <div class="p-4 border-b bg-gray-50">
                <form method="GET" id="filter" action="{{ route('purchases.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Vendor Filter -->
                        <div class="space-y-2">
                            <label for="vendor" class="block text-sm font-medium text-gray-700">Vendor</label>
                            <select name="vendor" id="vendor"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Semua Vendor</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}"
                                        {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Purchase ID Filter -->
                        <div class="space-y-2">
                            <label for="purchase_id" class="block text-sm font-medium text-gray-700">ID
                                Pembelian</label>
                            <input type="text" name="purchase_id" id="purchase_id"
                                value="{{ request('purchase_id') }}"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm"
                                placeholder="Cari ID Pembelian...">
                        </div>

                        <!-- Product Filter -->
                        <div class="space-y-2">
                            <label for="product" class="block text-sm font-medium text-gray-700">Produk</label>
                            <select name="product" id="product"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Semua Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ request('product') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Range Filter -->
                        <div class="space-y-2">
                            <label for="date_from" class="block text-sm font-medium text-gray-700">Tanggal Dari</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>

                        <div class="space-y-2">
                            <label for="date_to" class="block text-sm font-medium text-gray-700">Tanggal Sampai</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>

                        <!-- Entries per page -->
                        <div class="space-y-2">
                            <label for="entries" class="block text-sm font-medium text-gray-700">Tampilkan</label>
                            <select name="entries" id="entries"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 text-sm"
                                onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100] as $entry)
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
                        <a href="{{ route('purchases.index') }}"
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
                                No</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-24">
                                ID Pembelian</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-48">
                                Nama Vendor</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-32">
                                Tanggal Pembelian</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-40">
                                Total Amount</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-24">
                                Detail</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-32">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($purchases as $index => $purchase)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-center text-sm text-gray-900 whitespace-nowrap border-r">
                                    {{ $index + $purchases->firstItem() }}</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900 whitespace-nowrap border-r">
                                    {{ $purchase->id }}</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900 border-r">
                                    <span class="truncate block max-w-xs"
                                        title="{{ $purchase->vendor->name }}">{{ Str::limit($purchase->vendor->name, 20) }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900 whitespace-nowrap border-r">
                                    {{ $purchase->purchase_date->format('d-m-Y') }}</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900 whitespace-nowrap border-r">
                                    Rp{{ number_format($purchase->total_amount, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center border-r">
                                    <a href="{{ route('purchases.detail', $purchase->id) }}"
                                        class="inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 transition-colors duration-200">Lihat
                                        Detail</a>
                                </td>
                                <td class="py-3 text-center border-r">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('purchases.edit', $purchase->id) }}"
                                            class="inline-flex items-center justify-center px-7 py-1 bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200 transition-colors duration-200"><i
                                                class="fas fa-edit"></i> Edit</a>
                                        <button type="button" data-url="{{ route('purchases.destroy', $purchase->id) }}"
                                            class="delete-button inline-flex items-center justify-center px-7 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors duration-200"><i
                                                class="fas fa-trash"></i> Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada pembelian
                                    yang ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Card View -->
                <div class="sm:hidden divide-y divide-gray-200">
                    @forelse ($purchases as $index => $purchase)
                        <div class="p-4 bg-white hover:bg-gray-50">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">No:</span>
                                    <span class="text-sm text-gray-900">{{ $index + $purchases->firstItem() }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">ID Pembelian:</span>
                                    <span class="text-sm text-gray-900">{{ $purchase->id }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Vendor:</span>
                                    <span class="text-sm text-gray-900 truncate block max-w-xs"
                                        title="{{ $purchase->vendor->name }}">{{ Str::limit($purchase->vendor->name, 20) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Tanggal:</span>
                                    <span
                                        class="text-sm text-gray-900">{{ $purchase->purchase_date->format('d-m-Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Total:</span>
                                    <span
                                        class="text-sm text-gray-900">Rp{{ number_format($purchase->total_amount, 2, ',', '.') }}</span>
                                </div>

                                <!-- Mobile Actions -->
                                <div class="mt-4 flex flex-col space-y-2">
                                    <a href="{{ route('purchases.detail', $purchase->id) }}"
                                        class="w-full py-2 bg-blue-100 text-blue-600 rounded-md text-center text-sm font-medium hover:bg-blue-200 transition-colors duration-200">Lihat
                                        Detail</a>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('purchases.edit', $purchase->id) }}"
                                            class="py-2 bg-yellow-100 text-yellow-800 rounded-md text-center text-sm font-medium hover:bg-yellow-200 transition-colors duration-200"><i
                                                class="fas fa-edit"></i> Edit</a>
                                        <button type="button" data-url="{{ route('purchases.destroy', $purchase->id) }}"
                                            class="delete-button w-full py-2 bg-red-100 text-red-600 rounded-md text-sm font-medium hover:bg-red-200 transition-colors duration-200"><i
                                                class="fas fa-trash"></i> Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">Tidak ada pembelian yang ditemukan</div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination Section -->
            <div class="px-4 py-4 bg-gray-100 border-t">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600 text-center sm:text-left">
                        Menampilkan {{ $purchases->firstItem() ?? 0 }} hingga {{ $purchases->lastItem() ?? 0 }} dari
                        {{ $purchases->total() }} entri
                        @if (request()->hasAny(['search', 'vendor', 'product', 'date_from', 'date_to']))
                            <span class="block sm:inline sm:ml-2">(difilter dari {{ $purchases->total() }} total
                                entri)</span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @if ($purchases->onFirstPage())
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed"><i
                                    class="fas fa-chevron-left"></i> Previous</span>
                        @else
                            <a href="{{ $purchases->appends(request()->query())->previousPageUrl() }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"><i
                                    class="fas fa-chevron-left"></i> Previous</a>
                        @endif

                        @if ($purchases->hasMorePages())
                            <a href="{{ $purchases->appends(request()->query())->nextPageUrl() }}"
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.action = url;
                            form.method = 'POST';
    
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
    
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
    
                            form.appendChild(csrfToken);
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
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
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
    
</x-layout>

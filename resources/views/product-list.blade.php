<x-layout>
    <x-slot:folderName>Master Data</x-slot:folderName>

    <div class="container mx-auto px-4 py-4">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex flex-col gap-4 bg-gray-100 p-4 border-b">
                <h2 class="text-2xl font-bold text-gray-700">
                    <i class="fas fa-box"></i> Produk
                </h2>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <form method="GET" action="{{ route('products.index') }}" class="flex-1 min-w-[280px]">
                        <input type="hidden" name="entries" value="{{ $entries }}">
                        <div class="flex items-center border rounded-lg bg-white overflow-hidden w-full">
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Cari produk..." class="flex-1 px-4 py-2 focus:outline-none">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>

                    <form method="GET" action="{{ route('products.index') }}"
                        class="flex items-center space-x-2 min-w-fit">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <label for="entries" class="text-gray-600 whitespace-nowrap">Tampilkan</label>
                        <select id="entries" name="entries" class="border rounded-lg px-3 py-2 text-gray-700"
                            onchange="this.form.submit()">
                            <option value="10" {{ $entries == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $entries == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $entries == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $entries == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-gray-600">entri</span>
                    </form>

                    <form method="GET" action="{{ route('products.index') }}"
                        class="flex items-center space-x-2 min-w-fit">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="entries" value="{{ $entries }}">
                        <label for="month" class="text-gray-600">Filter Bulan</label>
                        <select id="month" name="month" class="border rounded-lg px-3 py-2 text-gray-700"
                            onchange="this.form.submit()">
                            <option value="current" {{ $month == 'current' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="previous" {{ $month == 'previous' ? 'selected' : '' }}>Bulan Lalu</option>
                            <option value="two_months_ago" {{ $month == 'two_months_ago' ? 'selected' : '' }}>2 Bulan
                                Lalu</option>
                            <option value="three_months_ago" {{ $month == 'three_months_ago' ? 'selected' : '' }}>3
                                Bulan Lalu</option>
                        </select>
                    </form>
                    <a href="{{ route('products.create') }}"
                        class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-semibold rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="py-3 px-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-12">
                                No</th>
                            <th
                                class="py-3 px-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r">
                                Nama Produk</th>
                            <th
                                class="hidden md:table-cell py-3 px-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r">
                                Deskripsi Produk</th>
                            <th
                                class="py-3 px-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r whitespace-nowrap">
                                Stok Awal</th>
                            <th
                                class="py-3 px-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r whitespace-nowrap">
                                Stok Sekarang</th>
                            <th
                                class="py-3 px-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($products as $index => $product)
                            <tr class="hover:bg-gray-300">
                                <td class="py-3 px-2 text-center border-r">{{ $products->firstItem() + $index }}</td>
                                <td class="py-3 px-4 text-center border-r">{{ $product->name }}</td>
                                <td class="hidden text-center md:table-cell py-3 px-4 border-r">
                                    {{ $product->description }}</td>
                                <td class="py-3 px-4 text-center border-r whitespace-nowrap">
                                    {{ $product->initial_stock }}
                                </td>
                                <td
                                    class="py-3 px-4 text-center border-r whitespace-nowrap {{ $product->current_stock < 10 ? 'text-red-500 font-bold' : '' }}">
                                    {{ $product->current_stock }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-4 justify-center">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            class="inline delete-product-form"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit"
                                                class="delete-product-btn text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center text-gray-500">
                                    Tidak ada produk yang ditemukan
                                    {{ $search ? "untuk kata kunci \"$search\"" : '' }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-4 bg-gray-100 border-t">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $products->firstItem() ?? 0 }} hingga {{ $products->lastItem() ?? 0 }} dari
                        {{ $products->total() }} entri
                        @if ($search)
                            <span class="block sm:inline sm:ml-2">(difilter dari {{ $products->total() }} total
                                entri)</span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @if ($products->onFirstPage())
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i> Previous
                            </span>
                        @else
                            <a href="{{ $products->appends(['search' => $search, 'entries' => $entries, 'month' => $month])->previousPageUrl() }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        @endif

                        @if ($products->hasMorePages())
                            <a href="{{ $products->appends(['search' => $search, 'entries' => $entries, 'month' => $month])->nextPageUrl() }}"
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
            const deleteButtons = document.querySelectorAll('.delete-product-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();

                    const form = button.closest('.delete-product-form');
                    const productId = form.querySelector('input[name="product_id"]').value;

                    try {
                        const response = await fetch(
                            `/products/${productId}/relationship-details`);
                        const data = await response.json();

                        // Cek apakah ada data terkait yang ditemukan
                        if (data && (data.purchases || data.orders)) {
                            let purchaseDetails = '';
                            let orderDetails = '';

                            // Jika ada data pembelian
                            if (Array.isArray(data.purchases) && data.purchases.length > 0) {
                                purchaseDetails = `
                                    <h4>Data Pembelian:</h4>
                                    <ul>
                                        ${data.purchases.map(purchase => 
                                            `<li>ID Pembelian: ${purchase.purchase_id}, Jumlah: ${purchase.quantity}, Tanggal: ${purchase.date}</li>`
                                        ).join('')}
                                    </ul>`;
                            }

                            // Jika ada data pesanan
                            if (Array.isArray(data.orders) && data.orders.length > 0) {
                                orderDetails = `
                                    <h4>Data Pesanan:</h4>
                                    <ul>
                                        ${data.orders.map(order => 
                                            `<li>ID Pesanan: ${order.order_id}, Jumlah: ${order.quantity}, Tanggal: ${order.date}</li>`
                                        ).join('')}
                                    </ul>`;
                            }

                            // Jika ada pembelian atau pesanan terkait
                            if (data.purchases?.length > 0 || data.orders?.length > 0) {
                                Swal.fire({
                                    title: 'Produk Tidak Dapat Dihapus',
                                    html: `
                                        <p>Produk ini masih terkait dengan beberapa data lainnya:</p>
                                        ${purchaseDetails}${orderDetails}
                                        <p>Silakan hapus data terkait terlebih dahulu sebelum menghapus produk ini.</p>`,
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                // Jika tidak ada data terkait, lanjutkan konfirmasi penghapusan produk
                                Swal.fire({
                                    title: 'Konfirmasi Penghapusan Produk',
                                    text: "Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Ya, Hapus!',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        form.submit();
                                    }
                                });
                            }
                        } else {
                            Swal.fire({
                                title: 'Terjadi Kesalahan',
                                text: 'Tidak dapat memuat informasi terkait produk ini. Mohon coba lagi nanti.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }

                    } catch (error) {
                        console.error(error);
                        Swal.fire({
                            title: 'Terjadi Kesalahan',
                            text: 'Ada masalah saat mengakses data dari server. Pastikan koneksi internet Anda baik.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Menampilkan alert berdasarkan session sukses atau error
            @if (session('success') || session('error'))
                Swal.fire({
                    icon: '{{ session('success') ? 'success' : 'error' }}',
                    title: '{{ session('success') ? 'Sukses' : 'Terjadi Kesalahan' }}',
                    text: '{{ session('success') ?? session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>

</x-layout>

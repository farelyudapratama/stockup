<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <form x-data="{ query: '' }" @submit.prevent="redirectToDetail" class="flex items-center">
                <input type="text" x-model="query" placeholder="Cari ID penjualan..."
                    class="w-full px-4 py-1.5 border rounded-l-lg border-sky-500 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold">Detail Penjualan</h1>
                <a href="{{ route('sales.index') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <div class="p-6">
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">StockUp</h2>
                    <!--<p class="text-gray-600">Jl. Contoh Jalan No. 123, Kota, Provinsi</p>-->
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Informasi Penjualan</h2>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-3 text-blue-600"></i>
                                <span class="font-medium text-gray-600">ID Penjualan:</span>
                                <span class="ml-2 text-gray-800">{{ $sale->id }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user mr-3 text-blue-600"></i>
                                <span class="font-medium text-gray-600">Nama Pembeli:</span>
                                <span class="ml-2 text-gray-800">{{ $sale->buyer_name }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-3 text-blue-600"></i>
                                <span class="font-medium text-gray-600">Tanggal Penjualan:</span>
                                <span class="ml-2 text-gray-800">{{ $sale->sale_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave mr-3 text-blue-600"></i>
                                <span class="font-medium text-gray-600">Total Penjualan:</span>
                                <span class="ml-2 text-green-600 font-bold">Rp
                                    {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Detail Pembayaran</h2>
                        <div class="bg-gray-100 rounded-lg p-4">
                            <table class="w-full text-sm">
                                <tr>
                                    <td class="py-1 text-gray-600">Total Item</td>
                                    <td class="text-right font-medium">{{ $sale->details->count() }} Produk</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-gray-600">Total Kuantitas</td>
                                    <td class="text-right font-medium">{{ $sale->details->sum('quantity') }} Unit</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Produk Terjual</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full bg-white shadow rounded-lg overflow-hidden">
                            <thead class="bg-blue-50 text-blue-700">
                                <tr>
                                    <th class="px-4 py-3 text-left">Produk</th>
                                    <th class="px-4 py-3 text-center">Harga</th>
                                    <th class="px-4 py-3 text-center">Kuantitas</th>
                                    <th class="px-4 py-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->details as $detail)
                                    <tr class="border-b hover:bg-gray-50 transition duration-200">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <span
                                                    class="font-medium text-gray-800">{{ $detail->product->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            Rp {{ number_format($detail->unit_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            {{ $detail->quantity }} Unit
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-green-600">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <a href="{{ route('sales.exportPDF', $sale->id) }}">
                        <button @click="printDetail"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300">
                            <i class="fas fa-print mr-2"></i>Cetak
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function redirectToDetail() {
            if (this.query.trim()) {
                window.location.href = `/sales/${this.query.trim()}/detail`;
            } else {
                alert("Masukkan ID penjualan untuk mencari.");
            }
        }
    </script>
</x-layout>

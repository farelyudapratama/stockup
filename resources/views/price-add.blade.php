<x-layout>
    <div class="min-h-screen bg-gray-100 flex items-start my-10 z-10 sm:w-8/12">
        <div class="max-w-screen-mdd w-full space-y-8 bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white text-center">Atur Harga Produk</h2>
            </div>

            <form action="{{ route('prices.store') }}" method="POST" class="px-6 py-8 space-y-6">
                @csrf

                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                    <select name="product_id" id="product_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        onchange="updatePrice(this)">
                        <option value="" disabled selected>Pilih produk...</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                data-price="{{ $product->productPrices->last()->price ?? '' }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="current_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Saat
                        Ini</label>
                    <input type="text" id="current_price" readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none bg-gray-100"
                        placeholder="Belum ada harga" value="">
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga Baru</label>
                    <input type="text" id="price" name="price" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        placeholder="Masukkan harga produk" min="0" oninput="formatInputRupiah(this)">
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('prices.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 transition duration-150 ease-in-out cursor-pointer">
                        Batal
                    </a>

                    <button type="submit" id="save-button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Simpan Harga
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updatePrice(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = selectedOption.getAttribute('data-price');

            // Format harga ke format IDR
            const formattedPrice = price ? formatRupiah(price) : 'Belum ada harga';

            document.getElementById('current_price').value = formattedPrice;
        }

        function formatRupiah(angka) {
            const cleanNumber = parseFloat(angka.toString().replace(/[^0-9]/g, ''));

            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            return formatter.format(cleanNumber || 0);
        }

        function formatInputRupiah(element) {
            let inputVal = element.value.replace(/[^,\d]/g, '');
            if (inputVal) {
                element.value = formatRupiah(inputVal);
            } else {
                element.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
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

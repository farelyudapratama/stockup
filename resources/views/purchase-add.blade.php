<x-layout>
    <div class="min-h-screen bg-gray-100 flex items-start justify-center z-10 px-2 my-10">
        <div class="w-full max-w-full sm:max-w-screen-lg space-y-6 bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="bg-blue-600 px-4 py-3 sm:px-6">
                <h2 class="text-lg sm:text-2xl font-bold text-white text-center">Tambah Pembelian Baru</h2>
            </div>

            <form action="{{ route('purchases.store') }}" method="POST" class="px-4 py-6 sm:px-6 space-y-4 sm:space-y-6">
                @csrf

                <!-- Pilih Vendor -->
                <div>
                    <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                    <select id="vendor_id" name="vendor_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        <option value="">Pilih Vendor</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pembelian -->
                <div>
                    <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                        Pembelian</label>
                    <input type="date" id="purchase_date" name="purchase_date" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                </div>

                <!-- Daftar Produk -->
                <div id="products-container">
                    <div class="product-item grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                            <select name="products[0][product_id]"
                                class="product-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                required>
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kuantitas</label>
                            <input type="number" name="products[0][quantity]" required min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Masukkan jumlah">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="text" name="products[0][unit_price]" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Masukkan harga" oninput="formatInputRupiah(this)">
                        </div>
                    </div>
                </div>

                <!-- Tombol untuk Menambah Produk -->
                <div class="flex justify-end">
                    <button type="button" id="add-product"
                        class="px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                        Tambah Produk
                    </button>
                </div>

                <!-- Total Harga -->
                <div>
                    <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                    <input type="text" id="total_amount" name="total_amount" readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out"
                        placeholder="Total harga akan dihitung otomatis">
                </div>

                <!-- Tombol Simpan -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('purchases.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 transition duration-150 ease-in-out cursor-pointer">
                        Batal
                    </a>
                    <button type="submit" id="save-button"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Simpan Pembelian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productsContainer = document.getElementById('products-container');
            const addProductButton = document.getElementById('add-product');
            const allProducts = @json($products);

            addProductButton.addEventListener('click', function() {
                if (productsContainer.children.length >= allProducts.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Semua produk sudah dipilih.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                const index = productsContainer.children.length;
                const productItem = `
                    <div class="product-item grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                            <select name="products[${index}][product_id]" class="product-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
                                <option value="">Pilih Produk</option>
                                ${allProducts.map(product => `<option value="${product.id}">${product.name}</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kuantitas</label>
                            <input type="number" name="products[${index}][quantity]" required min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Masukkan jumlah">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="text" name="products[${index}][unit_price]" required min="0" step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Masukkan harga" oninput="formatInputRupiah(this)">
                        </div>
                        <div class="flex items-center">
                            <button type="button" class="remove-product text-red-500 hover:text-red-700">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `;
                productsContainer.insertAdjacentHTML('beforeend', productItem);
                updateProductOptions();
                attachRemoveEvent();
                checkRemoveButtonState();
            });

            function updateProductOptions() {
                const selectedProducts = new Set(
                    Array.from(document.querySelectorAll('.product-select')).map(select => select.value)
                );

                document.querySelectorAll('.product-select').forEach(select => {
                    const currentValue = select.value;
                    const options = [`<option value="">Pilih Produk</option>`];

                    allProducts.forEach(product => {
                        if (!selectedProducts.has(product.id.toString()) || product.id
                            .toString() === currentValue) {
                            options.push(
                                `<option value="${product.id}" ${currentValue === product.id.toString() ? 'selected' : ''}>
                                    ${product.name}
                                </option>`
                            );
                        } else {
                            options.push(
                                `<option value="${product.id}" disabled>
                                    ${product.name} (Sudah dipilih)
                                </option>`
                            );
                        }
                    });

                    select.innerHTML = options.join('');
                });
            }

            function attachRemoveEvent() {
                document.querySelectorAll('.remove-product').forEach(button => {
                    button.addEventListener('click', function() {
                        this.closest('.product-item').remove();
                        updateProductOptions();
                        checkRemoveButtonState();
                    });
                });
            }

            function checkRemoveButtonState() {
                const removeButtons = document.querySelectorAll('.remove-product');
                const disable = productsContainer.children.length <= 1;
                removeButtons.forEach(button => button.disabled = disable);
            }

            function formatInputRupiah(input) {
                let value = input.value.replace(/[^0-9]/g, '');
                value = new Intl.NumberFormat('id-ID').format(value);
                input.value = value;
            }

            updateProductOptions();
            attachRemoveEvent();
            checkRemoveButtonState();

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

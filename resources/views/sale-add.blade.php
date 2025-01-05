<x-layout>
    <div class="min-h-screen bg-gray-100 flex items-start justify-center z-10 px-2 my-10">
        <div class="w-full max-w-full sm:max-w-screen-lg space-y-6 bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="bg-blue-600 px-4 py-3 sm:px-6">
                <h2 class="text-lg sm:text-2xl font-bold text-white text-center">Tambah Penjualan Baru</h2>
            </div>
            <form action="{{ route('sales.store') }}" method="POST" class="px-4 py-6 sm:px-6 space-y-4 sm:space-y-6">
                @csrf
                <div>
                    <label for="buyer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pembeli</label>
                    <input type="text" name="buyer_name" id="buyer_name" value="{{ old('buyer_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        required autofocus>
                </div>

                <div>
                    <label for="sale_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                        Penjualan</label>
                    <input type="date" id="sale_date" name="sale_date" value="{{ old('sale_date') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                </div>

                <div id="products-container">
                    @if (old('products'))
                        @foreach (old('products') as $index => $product)
                            <div class="product-item grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                                    <select name="products[{{ $index }}][product_id]"
                                        class="product-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        required>
                                        @foreach ($products as $productOption)
                                            <option value="{{ $productOption->id }}"
                                                {{ $productOption->id == $product['product_id'] ? 'selected' : '' }}>
                                                {{ $productOption->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kuantitas</label>
                                    <input type="number" name="products[{{ $index }}][quantity]"
                                        value="{{ $product['quantity'] }}" required min="1"
                                        class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        placeholder="Masukkan jumlah">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                                    <input type="text" name="products[{{ $index }}][unit_price]"
                                        value="{{ $product['unit_price'] }}" required
                                        class="price-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                        placeholder="Masukkan harga">
                                </div>
                                <div>
                                    <label for="sub_total" class="block text-sm font-medium text-gray-700 mb-1">Sub
                                        Total</label>
                                    <input type="text" id="sub_total" name="sub_total" readonly
                                        class="sub-total w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out"
                                        placeholder="Otomatis">
                                </div>
                                <div class="flex items-center">
                                    <button type="button" class="remove-product text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                    <div class="product-item grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                            <select name="products[0][product_id]"
                                class="product-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                required>
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        data-price="{{ optional($product->selling_price)->isEmpty() ? 0 : number_format($product->selling_price, 2) }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kuantitas</label>
                            <input type="number" name="products[0][quantity]" required min="1"
                                class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Masukkan jumlah">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="text" name="products[0][unit_price]" required readonly
                                class="price-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Harga akan terisi otomatis">
                        </div>
                        <div>
                            <label for="sub_total" class="block text-sm font-medium text-gray-700 mb-1">Sub
                                Total</label>
                            <input type="text" name="sub_total" readonly
                                class="sub-total w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out"
                                placeholder="Otomatis">
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end">
                    <button type="button" id="add-product"
                        class="px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                        Tambah Produk
                    </button>
                </div>

                <div>
                    <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                    <input type="text" id="total_amount" name="total_amount" value="{{ old('total_amount') }}"
                        readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out"
                        placeholder="Total harga akan dihitung otomatis">
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('sales.index') }}"
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
            allProducts = @json(
                $products->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'price' => $product->selling_price ?? null
                        ];
                    })->values());

            function handleProductSelection(select) {
                const productItem = select.closest('.product-item');
                const priceInput = productItem.querySelector('.price-input');
                const selectedOption = select.options[select.selectedIndex];
                const price = selectedOption.dataset.price;

                if (price !="null" && price) {
                    const formattedPrice = formatRupiah(price);
                    priceInput.value = formattedPrice;
                } else {
                    priceInput.value = 'Harga Tidak Tersedia';

                    // Swal.fire({
                    //     icon: 'warning',
                    //     title: 'Peringatan',
                    //     text: 'Harga produk ini tidak tersedia.',
                    //     confirmButtonText: 'OK',
                    //     cancelButtonText: 'Perbarui Harga',
                    //     cancelButtonColor: '#3085d6',
                    // });
                }

                calculateTotal();
            }

            // Tambah produk baru
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
                    <div class="product-item grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                            <select name="products[${index}][product_id]" class="product-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
                                <option value="">Pilih Produk</option>
                                ${allProducts.map(product => `<option value="${product.id}" data-price="${product.price}">${product.name}</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kuantitas</label>
                            <input type="number" name="products[${index}][quantity]" min="1" required
                                class="quantity-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Masukkan jumlah">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="text" name="products[${index}][unit_price]" required readonly
                                class="price-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Harga akan terisi otomatis">
                        </div>
                        <div>
                            <label for="sub_total" class="block text-sm font-medium text-gray-700 mb-1">Sub Total</label>
                            <input type="text" name="sub_total" readonly
                                class="sub-total w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none transition duration-150 ease-in-out"
                                placeholder="Otomatis">
                        </div>
                        <div class="flex items-center">
                            <button type="button" class="remove-product text-red-500 hover:text-red-700">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>`;

                productsContainer.insertAdjacentHTML('beforeend', productItem);

                const newSelect = productsContainer.lastElementChild.querySelector('.product-select');
                newSelect.addEventListener('change', function() {
                    handleProductSelection(this);
                });

                updateProductOptions();
                addEventListenersToInputs();
                calculateTotal();
            });

            // Fungsi untuk memperbarui pilihan produk
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
                                `<option value="${product.id}" data-price="${product.price}" ${currentValue === product.id.toString() ? 'selected' : ''}>
                                    ${product.name}
                                </option>`
                            );
                        } else {
                            options.push(
                                `<option value="${product.id}" disabled>${product.name} (Sudah dipilih)</option>`
                            );
                        }
                    });

                    select.innerHTML = options.join('');
                });
            }

            document.querySelectorAll('.product-select').forEach(select => {
                select.addEventListener('change', function() {
                    handleProductSelection(this);
                });
            });

            productsContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-product') || e.target.closest('.remove-product')) {
                    e.target.closest('.product-item').remove();
                    updateProductOptions();
                    calculateTotal();
                }
            });

            function calculateTotal() {
                const productItems = document.querySelectorAll('.product-item');
                let total = 0;

                productItems.forEach(item => {
                    const quantity = item.querySelector('input[name*="[quantity]"]').value || 0;
                    const unitPriceText = item.querySelector('input[name*="[unit_price]"]').value || '0';
                    
                    const unitPrice = parseRupiahToNumber(unitPriceText);
                    const subtotal = quantity * unitPrice;

                    item.querySelector('.sub-total').value = formatRupiah(subtotal.toString());
                    total += subtotal;
                });

                document.getElementById('total_amount').value = formatRupiah(total.toString());
            }

            function addEventListenersToInputs() {
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('input', calculateTotal);
                });

                document.querySelectorAll('.price-input').forEach(input => {
                    input.addEventListener('input', function() {
                        this.value = formatRupiah(parseRupiahToNumber(this.value).toString());
                        calculateTotal();
                    });
                });
            }

            function parseRupiahToNumber(rupiah) {
                return parseFloat(rupiah.replace(/[^\d]/g, '')) || 0;
            }

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            updateProductOptions();
            addEventListenersToInputs();
            calculateTotal();

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
                    html: `{{ session('error') }}`,
                    confirmButtonText: 'OK',
                    showCancelButton: {{ session('show_link_button') ? 'true' : 'false' }},
                    cancelButtonText: 'Perbarui Harga',
                    cancelButtonColor: '#3085d6',
                    didOpen: () => {
                        // Jika tombol cancel diklik, redirect ke halaman produk
                        if ({{ session('show_link_button') ? 'true' : 'false' }}) {
                            Swal.getCancelButton().addEventListener('click', () => {
                                window.location.href = '{{ session('product_url') }}';
                            });
                        }
                    }
                });
            @endif
        });
    </script>
</x-layout>

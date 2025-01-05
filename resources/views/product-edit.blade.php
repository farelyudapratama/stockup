<x-layout>

    <div class="min-h-screen bg-gray-100 flex items-start justify-center z-10 px-2 my-10">
        <div class="sm:max-w-screen-lg w-full space-y-8 bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white text-center">Edit Produk</h2>
            </div>

            <form action="{{ route('products.update', $product->id) }}" method="POST" class="px-6 py-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" id="name" name="name" value="{{ $product->name }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        placeholder="Masukkan nama produk">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi
                        Produk</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        placeholder="Masukkan deskripsi produk">{{ $product->description }}</textarea>
                </div>

                <div>
                    <label for="initial_stock" class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
                    <input type="number" id="initial_stock" name="initial_stock" required
                        value="{{ $product->initial_stock }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        placeholder="Masukkan jumlah stok awal" min="0">
                </div>

                <div>
                    <label for="current_stock" class="block text-sm font-medium text-gray-700 mb-1">Stok
                        Sekarang</label>
                    <input type="number" id="current_stock" name="current_stock" required
                        value="{{ $product->current_stock }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        placeholder="Masukkan jumlah stok sekarang" min="0">
                </div>

                <div>
                    <label for="reason_changed" class="block text-sm font-medium text-gray-700 mb-1">Alasan
                        Diubah</label>
                    <input type="text" id="reason_changed" name="reason_changed" required
                        value="{{ $product->reason_changed }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        placeholder="Masukkan Alasan Kenapa Diubah">
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 transition duration-150 ease-in-out cursor-pointer">
                        Batal
                    </a>
                    <button type="submit" id="save-button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const saveButton = document.getElementById('save-button');
        saveButton.addEventListener('click', function(event) {
            event.preventDefault();
            const form = document.querySelector('form');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            Swal.fire({
                title: 'Konfirmasi Simpan',
                text: "Apakah Anda yakin ingin menyimpan data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Tidak, batalkan',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form').submit();
                }
            });
        });
    </script>

</x-layout>

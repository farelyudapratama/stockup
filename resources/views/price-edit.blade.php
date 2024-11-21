<x-layout>
    <div class="min-h-screen bg-gray-100 flex items-start my-10 z-10 sm:w-8/12">
        <div class="max-w-screen-md w-full space-y-8 bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white text-center">Atur Harga: {{ $product->name }}</h2>
            </div>

            <form id="price-form" action="{{ route('prices.update', $product->id) }}" method="POST" class="px-6 space-y-6">
                @csrf
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                    <input type="text" id="price" name="price" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        placeholder="Masukkan harga baru" min="0" oninput="formatInputRupiah(this)">
                </div>

                <div class="flex items-center justify-between pb-8">
                    <a href="{{ route('prices.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 transition duration-150 ease-in-out">
                        Batal
                    </a>

                    <button type="submit" id="save-button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Simpan Harga
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
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
            const form = document.getElementById('price-form');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi Perubahan',
                    text: 'Apakah Anda yakin ingin mengubah data harga?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
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
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
</x-layout>

<x-layout>

    <div class="min-h-screen bg-gray-100 flex items-start justify-center z-10 px-2 my-10">
        <div class="w-full max-w-full sm:max-w-screen-md space-y-6 bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white text-center">Tambah Vendor Baru</h2>
            </div>

            <form action="{{ route('vendors.store') }}" method="POST" class="px-6 py-8 space-y-6">
                @csrf {{-- Harus pake ini bjir --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        placeholder="Masukkan nama produk">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Vendor</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        placeholder="Masukkan email vendor">
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('vendors.index') }}"
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
                        Simpan Vendor
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
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
                    title: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
</x-layout>

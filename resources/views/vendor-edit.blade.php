<x-layout>
    <div class="min-h-screen bg-gray-100 flex items-center z-10">
        <div class="max-w-screen-mdd w-full space-y-8 bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white text-center">Edit Vendor</h2>
            </div>

            <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" class="px-6 py-8 space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        value="{{ $vendor->name }}">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Vendor</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        value="{{ $vendor->email }}">
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" id="save-button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Update Vendor
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

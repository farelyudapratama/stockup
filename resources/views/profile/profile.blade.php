<x-layout>
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white border border-gray-300 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Profil Pengguna</h1>

        <!-- Pesan Sukses -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Pesan Error -->
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tabs -->
        <div class="flex justify-center space-x-4 mb-6">
            <button id="info-tab" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">Informasi</button>
            <button id="edit-tab" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">Edit Profil</button>
            <button id="password-tab" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">Ubah Password</button>
        </div>

        <!-- Tab Content -->
        <div id="info-content" class="tab-content">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pengguna</h2>
            <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Role:</strong> {{ Auth::user()->role }}</p>
        </div>

        <div id="edit-content" class="tab-content hidden">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Profil</h2>
            <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Simpan Perubahan</button>
            </form>
        </div>

        <div id="password-content" class="tab-content hidden">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Ubah Password</h2>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <input type="password" name="password" id="password" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Ubah Password</button>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = ['info', 'edit', 'password'];
            tabs.forEach(tab => {
                document.getElementById(`${tab}-tab`).addEventListener('click', () => {
                    tabs.forEach(t => {
                        document.getElementById(`${t}-content`).classList.add('hidden');
                        document.getElementById(`${t}-tab`).classList.remove('bg-blue-500', 'text-white');
                        document.getElementById(`${t}-tab`).classList.add('bg-gray-100', 'text-gray-700');
                    });
                    document.getElementById(`${tab}-content`).classList.remove('hidden');
                    document.getElementById(`${tab}-tab`).classList.remove('bg-gray-100', 'text-gray-700');
                    document.getElementById(`${tab}-tab`).classList.add('bg-blue-500', 'text-white');
                });
            });

            // Set default tab
            document.getElementById('info-tab').click();

            // Display SweetAlert for success or error
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Tutup'
                });
            @elseif(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Tutup'
                });
            @endif

            // Handle form submission with confirmation (Example: Edit Profile)
            document.getElementById('profileForm')?.addEventListener('submit', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Perubahan profil akan disimpan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
</x-layout>

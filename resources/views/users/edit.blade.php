<x-layout>
    <div class="max-w-lg mx-auto mt-8 p-6 bg-white border border-gray-300 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Edit Pengguna</h1>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ $user->email }}" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="stocker" {{ $user->role == 'stocker' ? 'selected' : '' }}>Stocker</option>
                    <option value="purchaser" {{ $user->role == 'purchaser' ? 'selected' : '' }}>Purchaser</option>
                    <option value="seller" {{ $user->role == 'seller' ? 'selected' : '' }}>Seller</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Perbarui Pengguna</button>
        </form>
    </div>
</x-layout>

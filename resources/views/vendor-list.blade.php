<x-layout>
    <x-slot:folderName>Master Data</x-slot:folderName>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex flex-col md:flex-row justify-between items-center bg-gray-100 px-6 py-4 border-b">
                <h2 class="text-2xl font-bold text-gray-700"><i class="fas fa-store"></i> Vendor</h2>
                <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 mt-4 md:mt-0">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('vendors.index') }}" class="flex items-center">
                        <input type="hidden" name="entries" value="{{ $entries }}">
                        <div class="flex items-center border rounded-lg bg-white overflow-hidden">
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Cari vendor..." class="px-4 py-2 focus:outline-none">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>

                    <!-- Entries Selector -->
                    <div class="flex items-center space-x-2">
                        <label for="entries" class="text-gray-600">Tampilkan</label>
                        <form method="GET" action="{{ route('vendors.index') }}">
                            <input type="hidden" name="search" value="{{ $search }}">
                            <select id="entries" name="entries" class="border rounded px-2 py-1 text-gray-700"
                                onchange="this.form.submit()">
                                <option value="10" {{ $entries == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $entries == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $entries == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $entries == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </form>
                        <span class="text-gray-600">entri</span>
                    </div>

                    <a href="{{ route('vendors.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-semibold rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg">
                        <i class="fas fa-plus"></i> Tambah Vendor
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th
                                class="py-3 px-2 bg-gray-50 border-b border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider border-r w-12">
                                No
                            </th>
                            <th
                                class="py-3 px-6 bg-gray-50 border-b border-gray-200 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider border-r">
                                Nama Vendor
                            </th>
                            <th
                                class="py-3 px-6 bg-gray-50 border-b border-gray-200 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider border-r">
                                Email
                            </th>
                            <th
                                class="py-3 px-6 bg-gray-50 border-b border-gray-200 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vendors as $index => $vendor)
                            <tr class="hover:bg-gray-200">
                                <td class="py-3 px-6 text-center border-b border-gray-200 border-r">
                                    {{ $vendors->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-6 text-center border-b border-gray-200 border-r">{{ $vendor->name }}
                                </td>
                                <td class="py-3 px-6 text-center border-b border-gray-200 border-r">
                                    {{ $vendor->email }}</td>
                                <td class="py-3 px-6 text-center border-b border-gray-200">
                                    <a href="{{ route('vendors.edit', $vendor->id) }}"
                                        class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i> Edit</a>
                                    <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST"
                                        class="inline-block ml-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 px-6 text-center text-gray-500">
                                    Tidak ada produk yang ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-100 px-6 py-4 border-t">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-600">
                        Tampilkan {{ $vendors->firstItem() ?? 0 }} hingga {{ $vendors->lastItem() ?? 0 }} dari
                        {{ $vendors->total() }}
                        entri
                        @if ($search)
                            <span class="ml-2">(filtered from {{ $vendors->total() }} total entries)</span>
                        @endif
                    </div>
                    <div class="flex space-x-4 mt-4 md:mt-0">
                        @if ($vendors->onFirstPage())
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded cursor-not-allowed"><i
                                    class="fas fa-chevron-left"></i> Previous</span>
                        @else
                            <a href="{{ $vendors->appends(['search' => $search, 'entries' => $entries])->previousPageUrl() }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><i
                                    class="fas fa-chevron-left"></i> Previous</a>
                        @endif

                        @if ($vendors->hasMorePages())
                            <a href="{{ $vendors->appends(['search' => $search, 'entries' => $entries])->nextPageUrl() }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Next <i
                                    class="fas fa-chevron-right"></i></a>
                        @else
                            <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded cursor-not-allowed">Next <i
                                    class="fas fa-chevron-right"></i></span>
                        @endif
                    </div>
                </div>
            </div>
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

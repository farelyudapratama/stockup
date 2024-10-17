<x-layout>
    <x-slot:folderName>Master Data</x-slot:folderName>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Branches</h2>
                <div class="flex items-center space-x-2">
                    <label for="entries">Show</label>
                    <select id="entries" class="border rounded px-2 py-1">
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <span>entries</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th
                                class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Nama Produk
                            </th>
                            <th
                                class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Harga
                            </th>
                            <th
                                class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Stok
                            </th>
                            <th
                                class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">Produk 1</td>
                            <td class="py-2 px-4 border-b border-gray-200">Rp 100.000</td>
                            <td class="py-2 px-4 border-b border-gray-200">50</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="#" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900 ml-4">Hapus</a>
                            </td>
                        </tr>
                        <!-- Tambahkan baris produk lainnya di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>

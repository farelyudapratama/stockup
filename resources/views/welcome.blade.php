<x-layout>
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
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b">Branch</th>
                            <th class="py-2 px-4 border-b">Phone Number</th>
                            <th class="py-2 px-4 border-b">Email</th>
                            <th class="py-2 px-4 border-b">Building</th>
                            <th class="py-2 px-4 border-b">Street</th>
                            <th class="py-2 px-4 border-b">City</th>
                            <th class="py-2 px-4 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($branches as $branch) --}}
                        <tr>
                            <td class="py-2 px-4 border-b">ss</td>
                            <td class="py-2 px-4 border-b">ss</td>
                            <td class="py-2 px-4 border-b">ss</td>
                            <td class="py-2 px-4 border-b">ss</td>
                            <td class="py-2 px-4 border-b">ss</td>
                            <td class="py-2 px-4 border-b">ss</td>
                            <td class="py-2 px-4 border-b">
                                <button class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>

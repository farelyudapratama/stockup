@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex" aria-label="Tabs">
                <button 
                    data-tab="stock-movement" 
                    class="tab-item w-1/3 py-4 text-center text-sm font-medium transition-colors duration-300 
                    flex items-center justify-center space-x-2
                    border-b-2 hover:text-blue-600 
                    focus:outline-none 
                    text-gray-500 hover:border-blue-600
                    border-transparent">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Pergerakan Stok</span>
                </button>
                
                <button 
                    data-tab="stock-in" 
                    class="tab-item w-1/3 py-4 text-center text-sm font-medium transition-colors duration-300 
                    flex items-center justify-center space-x-2
                    border-b-2 hover:text-green-600 
                    focus:outline-none 
                    text-gray-500 hover:border-green-600
                    border-transparent">
                    <i class="fas fa-arrow-down text-green-500"></i>
                    <span>Stok Masuk</span>
                </button>
                
                <button 
                    data-tab="stock-out" 
                    class="tab-item w-1/3 py-4 text-center text-sm font-medium transition-colors duration-300 
                    flex items-center justify-center space-x-2
                    border-b-2 hover:text-red-600 
                    focus:outline-none 
                    text-gray-500 hover:border-red-600
                    border-transparent">
                    <i class="fas fa-arrow-up text-red-500"></i>
                    <span>Stok Keluar</span>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="tab-content-container p-6">
            <!-- Pergerakan Stok Tab -->
            <div id="stock-movement" class="tab-content hidden">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h2 class="text-xl font-semibold text-blue-700 mb-4">Laporan Pergerakan Stok</h2>
                    
                    <form method="GET" action="{{ route('stock.index') }}" class="mb-4">
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Filter Produk</label>
                                <select name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="all">Semua Produk</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                            {{ $selectedProductId == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ $startDate }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                                <input type="date" name="end_date" value="{{ $endDate }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>
                        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Filter
                        </button>
                    </form>

                    <!-- Stock Movement Table -->
                    <div class="mt-4 bg-white shadow rounded-lg p-4">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 text-left">Tanggal</th>
                                    <th class="py-2 text-left">Produk</th>
                                    <th class="py-2 text-left">Jenis</th>
                                    <th class="py-2 text-right">Jumlah</th>
                                    <th class="py-2 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockMovements as $movement)
                                    <tr class="border-b">
                                        <td>{{ $movement->created_at->format('d M Y') }}</td>
                                        <td>{{ $movement->product->name }}</td>
                                        <td>
                                            <span class="{{ $movement->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $movement->type == 'in' ? 'Masuk' : 'Keluar' }}
                                            </span>
                                        </td>
                                        <td class="text-right">{{ $movement->quantity }}</td>
                                        <td>{{ $movement->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-gray-500 py-4">
                                            Tidak ada pergerakan stok
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Stok Masuk Tab -->
            <div id="stock-in" class="tab-content hidden">
                <div class="bg-green-50 p-4 rounded-lg">
                    <h2 class="text-xl font-semibold text-green-700 mb-4">Stok Masuk</h2>
                    
                    <form method="POST" action="{{ route('stock.add-in') }}" class="mb-4 bg-white p-4 rounded-lg shadow">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Produk</label>
                                <select name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                <input type="number" name="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="1">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            Tambah Stok Masuk
                        </button>
                    </form>

                    <!-- Stock In Table -->
                    <div class="bg-white shadow rounded-lg p-4">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 text-left">Tanggal</th>
                                    <th class="py-2 text-left">Produk</th>
                                    <th class="py-2 text-right">Jumlah</th>
                                    <th class="py-2 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockIn as $movement)
                                    <tr class="border-b">
                                        <td>{{ $movement->created_at->format('d M Y') }}</td>
                                        <td>{{ $movement->product->name }}</td>
                                        <td class="text-right">{{ $movement->quantity }}</td>
                                        <td>{{ $movement->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-500 py-4">
                                            Tidak ada stok masuk
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Stok Keluar Tab -->
            <div id="stock-out" class="tab-content hidden">
                <div class="bg-red-50 p-4 rounded-lg">
                    <h2 class="text-xl font-semibold text-red-700 mb-4">Stok Keluar</h2>
                    
                    <form method="POST" action="{{ route('stock.add-out') }}" class="mb-4 bg-white p-4 rounded-lg shadow">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Produk</label>
                                <select name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                <input type="number" name="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="1">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <button type="submit" class="mt-4 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            Catat Stok Keluar
                        </button>
                    </form>
    
                    <!-- Stock Out Table -->
                    <div class="bg-white shadow rounded-lg p-4">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 text-left">Tanggal</th>
                                    <th class="py-2 text-left">Produk</th>
                                    <th class="py-2 text-right">Jumlah</th>
                                    <th class="py-2 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockOut as $movement)
                                    <tr class="border-b">
                                        <td>{{ $movement->created_at->format('d M Y') }}</td>
                                        <td>{{ $movement->product->name }}</td>
                                        <td class="text-right">{{ $movement->quantity }}</td>
                                        <td>{{ $movement->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-500 py-4">
                                            Tidak ada stok keluar
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Default to first tab
            showTab('stock-movement');
    
            // Tab switching logic
            document.querySelectorAll('.tab-item').forEach(item => {
                item.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    showTab(tabId);
                });
            });
    
            function showTab(tabId) {
                // Remove active states from all tabs and content
                document.querySelectorAll('.tab-item').forEach(tab => {
                    tab.classList.remove('border-blue-600', 'border-green-600', 'border-red-600', 'text-blue-600', 'text-green-600', 'text-red-600');
                    tab.classList.add('border-transparent', 'text-gray-500');
                });
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
    
                // Add active state to selected tab
                const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
                const activeContent = document.getElementById(tabId);
    
                activeTab.classList.remove('border-transparent', 'text-gray-500');
                switch(tabId) {
                    case 'stock-movement':
                        activeTab.classList.add('border-blue-600', 'text-blue-600');
                        break;
                    case 'stock-in':
                        activeTab.classList.add('border-green-600', 'text-green-600');
                        break;
                    case 'stock-out':
                        activeTab.classList.add('border-red-600', 'text-red-600');
                        break;
                }
    
                activeContent.classList.remove('hidden');
            }
        });
    </script>
    @endpush
    @endsection
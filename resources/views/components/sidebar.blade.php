<aside x-show="isAsideOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-x-[-100%] opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-[-100%] opacity-0"
    class="bg-gray-800 text-white w-full h-full fixed inset-0 z-50 overflow-auto md:w-64 md:static md:h-auto md:overflow-hidden">
    <nav class="p-4">
        <div class="mb-4 flex justify-between items-center md:hidden">
            <h2 class="text-xl font-bold">Menu</h2>
            <button @click="isAsideOpen = false" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <div x-data="{ openFolders: {} }">
            <div class="mb-2">
                <button @click="openFolders['Kelola Master Data'] = !openFolders['Kelola Master Data']"
                    class="{{ request()->is(['products', 'vendors', 'prices']) ? 'bg-cyan-700' : 'text-white ' }} flex justify-between items-center w-full py-2 px-4 text-left rounded hover:bg-gray-700 focus:outline-none transition-colors duration-200">
                    <span>Master Data</span>
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="{ 'rotate-90': openFolders['Kelola Produk'] }" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <div x-show="openFolders['Kelola Master Data']" x-transition class="pl-4">
                    <a href="/products"
                        class="{{ request()->is('products') ? 'bg-cyan-800' : 'text-white ' }} block py-2 px-4 rounded hover:bg-gray-700 transition-colors duration-200">
                        Produk</a>
                    <a href="/prices"
                        class=" {{ request()->is('prices') ? 'bg-cyan-800' : 'text-white ' }} block py-2 px-4 rounded hover:bg-gray-700 transition-colors duration-200">
                        Harga</a>
                    <a href="/vendors"
                        class="{{ request()->is('vendors') ? 'bg-cyan-800' : 'text-white' }} block py-2 px-4 rounded hover:bg-gray-700 transition-colors duration-200">
                        Vendor</a>
                </div>
            </div>
            <div class="mb-2">
                <button @click="openFolders['Kelola Transaksi'] = !openFolders['Kelola Transaksi']"
                    class="text-white flex justify-between items-center w-full py-2 px-4 text-left rounded hover:bg-gray-700 focus:outline-none transition-colors duration-200">
                    <span>Transaksi</span>
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="{ 'rotate-90': openFolders['Kelola Produk'] }" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <div x-show="openFolders['Kelola Transaksi']" x-transition class="pl-4">
                    <a href="#"
                        class="text-white block py-2 px-4 rounded hover:bg-gray-700 transition-colors duration-200">
                        Pembelian</a>
                </div>
            </div>
            <div class="mb-2">
                <button @click="openFolders['Kelola Laporan'] = !openFolders['Kelola Laporan']"
                    class="text-white flex justify-between items-center w-full py-2 px-4 text-left rounded hover:bg-gray-700 focus:outline-none transition-colors duration-200">
                    <span>Laporan</span>
                    <svg class="w-4 h-4 transition-transform duration-200"
                        :class="{ 'rotate-90': openFolders['Kelola Produk'] }" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <div x-show="openFolders['Kelola Laporan']" x-transition class="pl-4">
                    <a href="#"
                        class="text-white block py-2 px-4 rounded hover:bg-gray-700 transition-colors duration-200">
                        Laporan Pembelian</a>
                    <a href="#"
                        class="text-white block py-2 px-4 rounded hover:bg-gray-700 transition-colors duration-200">
                        Laporan Stok</a>
                </div>
            </div>
        </div>
    </nav>
</aside>
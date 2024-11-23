<nav class="bg-gray-800 relative z-50">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
            <div class="inset-y-0 left-0 flex items-center">
                <!-- Mobile menu button (now visible on all screens) -->
                <button type="button" @click="isAsideOpen = !isAsideOpen" id="mobile-menu-button"
                    class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white mr-2"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <!--
              Icon when menu is closed.
  
              Menu open: "hidden", Menu closed: "block"
            -->
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" aria-hidden="true" data-slot="icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <!--
              Icon when menu is open.
  
              Menu open: "block", Menu closed: "hidden"
            -->
                    <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" aria-hidden="true" data-slot="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                <div class="flex flex-shrink-0 items-center">
                    <a href="/" class="font-extrabold text-3xl text-white">StockUp</a>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                <div x-data="{ isDropdownOpen: false, openFolders: {} }" class="relative">
                    <button @click="isDropdownOpen = !isDropdownOpen"
                        class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                        <span class="absolute -inset-1.5"></span>
                        <span class="sr-only">Add Button</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>

                    <!-- Dropdown menu with collapsible sections -->
                    <div x-show="isDropdownOpen" @click.away="isDropdownOpen = false"
                        x-transition:enter="transition ease-out duration-100 transform"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75 transform"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">

                        <!-- Collapsible Kelola Produk -->
                        <div class="px-4 py-2 text-gray-700">
                            <button @click="openFolders['Kelola Produk'] = !openFolders['Kelola Produk']"
                                class="flex justify-between items-center w-full py-2 px-4 text-left rounded hover:bg-gray-100 focus:outline-none transition-colors duration-200">
                                <span>Kelola Produk</span>
                                <svg class="w-4 h-4 transition-transform duration-200"
                                    :class="{ 'rotate-90': openFolders['Kelola Produk'] }"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <div x-show="openFolders['Kelola Produk']" x-transition class="pl-4">
                                <a href="/product/add"
                                    class="block py-2 px-4 rounded hover:bg-gray-100 transition-colors duration-200">Tambah
                                    Produk</a>
                                <a href="/price/add"
                                    class="block py-2 px-4 rounded hover:bg-gray-100 transition-colors duration-200">Tambah
                                    Harga</a>
                            </div>
                        </div>

                        <!-- Collapsible Kelola Vendor -->
                        <div class="px-4 py-2 text-gray-700">
                            <button @click="openFolders['Kelola Vendor'] = !openFolders['Kelola Vendor']"
                                class="flex justify-between items-center w-full py-2 px-4 text-left rounded hover:bg-gray-100 focus:outline-none transition-colors duration-200">
                                <span>Kelola Vendor</span>
                                <svg class="w-4 h-4 transition-transform duration-200"
                                    :class="{ 'rotate-90': openFolders['Kelola Vendor'] }"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <div x-show="openFolders['Kelola Vendor']" x-transition class="pl-4">
                                <a href="/vendor/add"
                                    class="block py-2 px-4 rounded hover:bg-gray-100 transition-colors duration-200">Tambah
                                    Vendor</a>
                            </div>
                        </div>

                        <!-- Collapsible Pembelian -->
                        <div class="px-4 py-2 text-gray-700">
                            <button @click="openFolders['Transaksi'] = !openFolders['Transaksi']"
                                class="flex justify-between items-center w-full py-2 px-4 text-left rounded hover:bg-gray-100 focus:outline-none transition-colors duration-200">
                                <span>Transaksi</span>
                                <svg class="w-4 h-4 transition-transform duration-200"
                                    :class="{ 'rotate-90': openFolders['Transaksi'] }"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <div x-show="openFolders['Transaksi']" x-transition class="pl-4">
                                <a href="/purchase/add"
                                    class="block py-2 px-4 rounded hover:bg-gray-100 transition-colors duration-200">Tambah
                                    Pembelian</a>
                                <a href="/sale/add"
                                    class="block py-2 px-4 rounded hover:bg-gray-100 transition-colors duration-200">Tambah
                                    Penjualan</a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Profile dropdown -->
                <div class="relative ml-3">
                    <div>
                        <button type="button" @click="isOpen = !isOpen"
                            class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full"
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="">
                        </button>
                    </div>

                    <!--
              Dropdown menu, show/hide based on menu state.
  
              Entering: "transition ease-out duration-100"
                From: "transform opacity-0 scale-95"
                To: "transform opacity-100 scale-100"
              Leaving: "transition ease-in duration-75"
                From: "transform opacity-100 scale-100"
                To: "transform opacity-0 scale-95"
            -->
                    <div x-show="isOpen" @click.away="isOpen = false"
                        x-transition:enter="transition ease-out duration-100 transform"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75 transform"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                        tabindex="-1">
                        <!-- Active: "bg-gray-100", Not Active: "" -->
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem"
                            tabindex="-1" id="user-menu-item-0">Your Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem"
                            tabindex="-1" id="user-menu-item-1">Settings</a>
                        <a href="logout" class="block px-4 py-2 text-sm text-gray-700" role="menuitem"
                            tabindex="-1" id="user-menu-item-2">Sign out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

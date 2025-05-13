<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Select2 CSS (di <head>) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS (sebelum </body>) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/typewriter-effect@2.18.0/dist/core.js"></script>



    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-100 flex">

        <!-- Sidebar -->
        <div class="mt-3 ml-3  mb-3 w-64 bg-white text-black p-4 shadow-md rounded-lg">
            <!-- Menu -->
            <nav class="space-y-4">
                <!-- Dashboard -->
                <!-- Wrapper menu -->
                <div class="space-y-2">
                    <x-nav-link :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        class="w-full px-4 py-2 text-left rounded-md 
                    {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Dashboard
                    </x-nav-link>
                    @if(auth()->user()->role === 'admindept_hcm')
                    <x-nav-link :href="route('spd.index')"
                        :active="request()->routeIs('spd.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('spd.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Data SPD
                    </x-nav-link>
                    <x-nav-link :href="route('dpd.index')"
                        :active="request()->routeIs('dpd.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('dpd.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Data DPD
                    </x-nav-link>
                    <x-nav-link :href="route('departemen.index')"
                        :active="request()->routeIs('departemen.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('departemen.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Data Departemen
                    </x-nav-link>
                    <x-nav-link :href="route('pegawai.index')"
                        :active="request()->routeIs('pegawai.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('pegawai.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Data Pegawai
                    </x-nav-link>
                    <x-nav-link :href="route('rancangan.index')"
                        :active="request()->routeIs('rancangan.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('rancangan.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Pengajuan Anggaran
                    </x-nav-link>
                    @elseif(auth()->user()->role === 'tmhcm')
                    <x-nav-link :href="route('periode.index')"
                        :active="request()->routeIs('periode.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('periode.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Data Periode
                    </x-nav-link>
                    <x-nav-link :href="route('rancangan.index')"
                        :active="request()->routeIs('rancangan.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('rancangan.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Pengajuan Anggaran
                    </x-nav-link>
                    @elseif(auth()->user()->role === 'admindept')
                    <x-nav-link :href="route('rancangan.index')"
                        :active="request()->routeIs('rancangan.index')"
                        class="w-full px-4 py-2 text-left rounded-md {{ request()->routeIs('rancangan.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Pengajuan Anggaran
                    </x-nav-link>
                    @if(auth()->user()->email === 'finec@admindept.com')
                    <x-nav-link :href="route('spd.pengajuan')"
                        :active="request()->routeIs('spd.pengajuan')"
                        class="w-full px-4 py-2 text-left rounded-md
                        {{ request()->routeIs('spd.pengajuan') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Pengajuan SPD
                    </x-nav-link>
                    @endif
                    @endif



                </div>
            </nav>
        </div>

        <!-- Konten Utama -->
        <div class="flex-1 bg-gray-180 p-6">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
            <header class="bg-white shadow rounded-lg">
                <div class="py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
                @include('sweetalert::alert')
            </main>
            @stack('scripts')
        </div>

    </div>

    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = [{
                    buttonId: 'spdDropdown',
                    menuId: 'spdDropdownMenu'
                },
                {
                    buttonId: 'departemenDropdown',
                    menuId: 'departemenDropdownMenu'
                },
                {
                    buttonId: 'periodeDropdown',
                    menuId: 'periodeDropdownMenu'
                }
            ];

            dropdowns.forEach(({
                buttonId,
                menuId
            }) => {
                const button = document.getElementById(buttonId);
                const menu = document.getElementById(menuId);

                if (button && menu) {
                    button.addEventListener('click', function() {
                        const isOpen = menu.classList.contains('block');

                        // Sembunyikan semua dropdown lain
                        dropdowns.forEach(({
                            menuId: otherMenuId
                        }) => {
                            const otherMenu = document.getElementById(otherMenuId);
                            if (otherMenu && otherMenu !== menu) {
                                otherMenu.classList.add('hidden');
                                otherMenu.classList.remove('block');
                            }
                        });

                        // Toggle menu yang diklik
                        if (isOpen) {
                            menu.classList.add('hidden');
                            menu.classList.remove('block');
                        } else {
                            menu.classList.remove('hidden');
                            menu.classList.add('block');
                        }
                    });
                }
            });

            // Klik di luar menu akan menutup dropdown
            document.addEventListener('click', function(event) {
                const isClickInsideDropdown = dropdowns.some(({
                    buttonId,
                    menuId
                }) => {
                    const button = document.getElementById(buttonId);
                    const menu = document.getElementById(menuId);
                    return button?.contains(event.target) || menu?.contains(event.target);
                });

                if (!isClickInsideDropdown) {
                    dropdowns.forEach(({
                        menuId
                    }) => {
                        const menu = document.getElementById(menuId);
                        if (menu) {
                            menu.classList.add('hidden');
                            menu.classList.remove('block');
                        }
                    });
                }
            });
        });
    </script> -->

    <script>
        $(document).ready(function() {
            $('#periodeSelect').select2({
                placeholder: "Pilih Periode",
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>

</body>

</html>
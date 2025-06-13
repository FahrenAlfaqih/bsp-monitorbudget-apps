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
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/typewriter-effect@2.18.0/dist/core.js"></script>





    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased">


    <div class="min-h-screen flex">

        <aside class="fixed top-0 left-0 h-full w-64 text-black p-4 shadow-md rounded-r-lg overflow-auto">

            <nav class="flex flex-col items-center space-y-4">
                <div class="space-y-2">
                    <x-nav-link :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        class="w-full px-4 py-2 text-left rounded-md 
                    {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Dashboard
                    </x-nav-link>

                    @if(auth()->user()->role === 'admindept_hcm')
                    <a class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 cursor-default select-none">
                        Manajemen Laporan SPD
                    </a>
                    <x-nav-link :href="route('spd.index')"
                        :active="request()->routeIs('spd.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('spd.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Pelaporan SPD
                    </x-nav-link>
                    <x-nav-link :href="route('dpd.index')"
                        :active="request()->routeIs('dpd.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('dpd.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Data DPD
                    </x-nav-link>
                    <a class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 cursor-default select-none">
                        Manajemen Master Data
                    </a>
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
                    <a class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 cursor-default select-none">
                        Manajemen Anggaran Dinas
                    </a>
                    <x-nav-link :href="route('rancangan.index')"
                        :active="request()->routeIs('rancangan.index')"
                        class="w-full px-4 py-2 text-left rounded-md mb-3 
                       {{ request()->routeIs('rancangan.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Pengajuan Anggaran
                    </x-nav-link>
                    @elseif(auth()->user()->role === 'tmhcm')
                    <a class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 cursor-default select-none">
                        Manajemen Anggaran Dinas
                    </a>
                    <x-nav-link :href="route('periode.index')"
                        :active="request()->routeIs('periode.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('periode.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Data Periode Pengajuan
                    </x-nav-link>
                    <x-nav-link :href="route('rancangan.index')"
                        :active="request()->routeIs('rancangan.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('rancangan.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Pengajuan Anggaran
                    </x-nav-link>
                    <a class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 cursor-default select-none">
                        Realisasi Anggaran Dinas
                    </a>
                    <x-nav-link :href="route('dpd.index')"
                        :active="request()->routeIs('dpd.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('dpd.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Rekap DPD
                    </x-nav-link>
                    <x-nav-link :href="route('spd.index')"
                        :active="request()->routeIs('spd.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('spd.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Rekap Pelaporan SPD
                    </x-nav-link>
                    @elseif(auth()->user()->role === 'admindept')
                    <a class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 cursor-default select-none">
                        Manajemen Anggaran Dinas
                    </a>
                    <x-nav-link :href="route('dpd.index')"
                        :active="request()->routeIs('dpd.index')"
                        class="w-full px-4 py-2 text-left rounded-md 
                       {{ request()->routeIs('dpd.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Rekap Laporan DPD
                    </x-nav-link>
                    <x-nav-link :href="route('rancangan.index')"
                        :active="request()->routeIs('rancangan.index')"
                        class="w-full px-4 py-2 text-left rounded-md {{ request()->routeIs('rancangan.index') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Pengajuan Anggaran
                    </x-nav-link>

                    @if(auth()->user()->email === 'finec@admindept.com')
                    <a class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 cursor-default select-none">
                        Pelaporan dari HCM
                    </a>
                    <x-nav-link :href="route('spd.pengajuan')"
                        :active="request()->routeIs('spd.pengajuan')"
                        class="w-full px-4 py-2 text-left rounded-md
                        {{ request()->routeIs('spd.pengajuan') ? 'bg-blue-500 text-white' : 'text-gray-700' }}">
                        Pelaporan SPD
                    </x-nav-link>
                    @endif
                    @endif



                </div>
            </nav>
        </aside>

        <div class="ml-64 flex-1 flex flex-col min-h-screen">
            <!-- Header fixed -->
            <header class="fixed top-0 left-64 right-0  p-6 z-30 shadow-sm bg-white rounded-md ml-3">
                @include('layouts.navigation')
                @if (isset($header))
                <div>
                    {{ $header }}
                </div>
                @endif
            </header>

            <div class=" h-24">
            </div>

            <!-- Konten utama scrollable -->
            <main class="flex-1 overflow-auto p-10 bg-gray-50">

                {{ $slot }}
                @include('sweetalert::alert')
            </main>

            @stack('scripts')
        </div>

    </div>

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
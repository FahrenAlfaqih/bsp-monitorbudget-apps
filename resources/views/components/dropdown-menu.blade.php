@props(['id', 'title', 'createRoute', 'indexRoute', 'createLabel' => 'Tambah Data', 'indexLabel' => 'Lihat Data', 'icon' => 'fa-icon-default'])

<div class="relative">
    <button
        class="w-full px-4 py-2 text-left rounded-md {{ request()->routeIs($createRoute) || request()->routeIs($indexRoute) ? 'bg-purple-800 text-white' : 'text-gray-700' }}"
        id="{{ $id }}Dropdown">
        <i class="{{ $icon }}"></i>
        {{ $title }}
    </button>
    <div id="{{ $id }}DropdownMenu"
        class="w-full bg-white shadow-md mt-2 rounded-md hidden transition-all duration-300">
        <x-nav-link :href="route($createRoute)"
            class="block px-4 py-2 {{ request()->routeIs($createRoute) ? 'bg-purple-800 text-white' : 'text-gray-700' }}">
            {{ $createLabel }}
        </x-nav-link>
        <x-nav-link :href="route($indexRoute)"
            class="block px-4 py-2 {{ request()->routeIs($indexRoute) ? 'bg-purple-800 text-white' : 'text-gray-700' }}">
            {{ $indexLabel }}
        </x-nav-link>
    </div>
</div>
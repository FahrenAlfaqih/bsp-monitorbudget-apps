<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Departemen
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-md rounded-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                <h3 class="font-semibold text-lg text-gray-800">
                    Daftar Departemen
                </h3>
                <a href="{{ route('departemen.create') }}" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">Tambah Departemen</a>

            </div>
            @if($departemen->isEmpty())
            <p class="mt-2 text-gray-500">Data departemen tidak tersedia.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-separate border-spacing-0 mt-4">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="py-3 px-4 text-sm font-medium">Nama Departemen</th>
                            <th class="py-3 px-4 text-sm font-medium">Nomor BS</th>
                            <th class="py-3 px-4 text-sm font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departemen as $d)
                        <tr class="border-b hover:bg-gray-50 transition duration-300">
                            <td class="py-3 px-4 text-sm">{{ $d->nama }}</td>
                            <td class="py-3 px-4 text-sm">{{ $d->bs_number }}</td>
                            <td class="py-3 px-4 text-sm">
                                <!-- <a href="{{ route('departemen.edit', $d->id) }}" class="text-blue-500 hover:text-blue-700 text-xs">Edit</a> -->
                                <a href="{{ route('departemen.edit', $d->id) }}"
                                    class="inline-block px-3 py-1 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg transition">
                                    Edit
                                </a>
                            </td>


                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
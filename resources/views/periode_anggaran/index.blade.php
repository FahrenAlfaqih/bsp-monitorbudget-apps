<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Periode Anggaran
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Card Periode Anggaran -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Periode Anggaran</h3>
                <a href="{{ route('periode.create') }}" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">Tambah Periode</a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="py-3 px-4 text-sm font-medium">Nama Periode</th>
                            <th class="py-3 px-4 text-sm font-medium">Mulai</th>
                            <th class="py-3 px-4 text-sm font-medium">Berakhir</th>
                            <th class="py-3 px-4 text-sm font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($periodes as $periode)
                        <tr class="border-b hover:bg-gray-50 transition duration-300">
                            <td class="py-3 px-4 text-sm">{{ $periode->nama_periode }}</td>
                            <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($periode->mulai)->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($periode->berakhir)->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-sm text-center">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $periode->status == 'dibuka' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($periode->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
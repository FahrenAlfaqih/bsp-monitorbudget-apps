<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Data Deklarasi Perjalanan Dinas
            </h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Card Daftar DPD -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Deklarasi Perjalanan Dinas</h3>
                <a href="{{ route('dpd.create') }}" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">Tambah DPD</a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="py-3 px-4 text-sm font-medium">Nomor SPD</th>
                            <th class="py-3 px-4 text-sm font-medium">Tanggal Deklarasi</th>
                            <th class="py-3 px-4 text-sm font-medium">Total Biaya</th>
                            <th class="py-3 px-4 text-sm font-medium">Uraian</th>
                            <!-- <th class="py-3 px-4 text-sm font-medium">Aksi</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deklarasi as $dpd)
                        <tr class="border-b hover:bg-gray-50 transition duration-300">
                            <td class="py-3 px-4 text-sm">{{ $dpd->spd->nomor_spd ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($dpd->tanggal_deklarasi)->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-sm">Rp {{ number_format((float) $dpd->total_biaya, 1, ',', '.') }}</td>
                            <td class="py-3 px-4 text-sm">{{ $dpd->uraian ?? 'N/A' }}</td>
                            <!-- <td class="py-3 px-4 text-sm">
                                <a href="{{ route('dpd.edit', $dpd->id) }}" class="text-yellow-500 hover:text-yellow-700 mr-3">Edit</a>
                                <form action="{{ route('dpd.destroy', $dpd->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
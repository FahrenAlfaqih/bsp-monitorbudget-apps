<!-- resources/views/spd/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar SPD
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Card Daftar SPD -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

            <form action="{{ route('spd.ajukan') }}" method="POST">
                @csrf
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-700">Daftar Surat Perjalanan Dinas</h3>
                    <div class="flex gap-2 mb-4">
                        <a href="{{ route('spd.create') }}" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">Tambah SPD</a>
                        <button type="submit" class="px-6 py-2.5 text-white bg-green-600 hover:bg-green-700 font-medium text-sm rounded-lg shadow-md transition">
                            Ajukan SPD ke Finance
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-left border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600">
                                <th class="py-3 px-4 text-sm font-medium">
                                    <input type="checkbox" id="checkAll" class="form-checkbox h-4 w-4 text-blue-600">
                                </th>
                                <th class="py-3 px-4 text-sm font-medium">Nomor SPD</th>
                                <th class="py-3 px-4 text-sm font-medium">Nama Pegawai</th>
                                <th class="py-3 px-4 text-sm font-medium">Asal</th>
                                <th class="py-3 px-4 text-sm font-medium">Tujuan</th>
                                <th class="py-3 px-4 text-sm font-medium">Kegiatan</th>
                                <th class="py-3 px-4 text-sm font-medium">Tanggal Berangkat</th>
                                <th class="py-3 px-4 text-sm font-medium">Tanggal Kembali</th>
                                <th class="py-3 px-4 text-sm font-medium">Jenis Transport</th>
                                <th class="py-3 px-4 text-sm font-medium">Nama Transport</th>
                                <th class="py-3 px-4 text-sm font-medium">Departemen</th>
                                <th class="py-3 px-4 text-sm font-medium">Status</th>
                                <th class="py-3 px-4 text-sm font-medium">Catatan</th>
                                <th class="py-3 px-4 text-sm font-medium">Tanggal Deklarasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($spds as $spd)
                            <tr class="border-b hover:bg-gray-50 transition duration-300">
                                <td class="py-3 px-4 text-sm">
                                    <input type="checkbox" name="spd_ids[]" value="{{ $spd->id }}" class="form-checkbox h-4 w-4 text-blue-600">
                                </td>
                                <td class="py-3 px-4 text-sm">{{ $spd->nomor_spd }}</td>
                                <td class="py-3 px-4 text-sm">{{ $spd->nama_pegawai }}</td>
                                <td class="py-3 px-4 text-sm">{{ $spd->asal }}</td>
                                <td class="py-3 px-4 text-sm">{{ $spd->tujuan }}</td>
                                <td class="py-3 px-4 text-sm">{{ $spd->kegiatan }}</td>
                                <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($spd->tanggal_berangkat)->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($spd->tanggal_kembali)->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-sm">{{ $spd->jenis_transport }}</td>
                                <td class="py-3 px-4 text-sm">{{ $spd->nama_transport }}</td>
                                <td class="py-3 px-4 text-sm">{{ $spd->departemen->nama ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $spd->status == 'menunggu' ? 'bg-yellow-100 text-yellow-800' : ($spd->status == 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($spd->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm">{{ $spd->uraian }}</td>
                                <td class="py-3 px-4 text-sm">{{ $spd->tanggal_deklarasi }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('checkAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="spd_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>

</x-app-layout>
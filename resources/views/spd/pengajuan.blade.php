<x-app-layout>


    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- Panduan Pengelolaan Pengajuan SPD --}}
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2 text-base">Panduan Pengelolaan Pengajuan Pelaporan SPD</h3>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Status SPD:</strong>
                    <ul class="list-disc list-inside ml-5">
                        <li><span class="font-semibold">Diajukan:</span> SPD sedang menunggu persetujuan. Anda dapat mengubah status melalui tombol <em>Edit Status</em>.</li>
                        <li><span class="font-semibold">Disetujui:</span> SPD telah disetujui dan dapat dilanjutkan untuk pembuatan DPD.</li>
                        <li><span class="font-semibold">Ditolak:</span> SPD ditolak dan tidak dapat diproses lebih lanjut. Tombol aksi akan dinonaktifkan.</li>
                    </ul>
                </li>
                <li><strong>Tombol Edit Status:</strong> Hanya tersedia untuk SPD berstatus <em>diajukan</em>
                <li><strong>Pilih departemen</strong> Untuk menampilkan pengajuan SPD berdasarkan departemen
                </li>
            </ul>
        </div>

        <div class="bg-white p-6 shadow-md rounded-lg mb-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4">Filter Pengajuan Pelaporan SPD</h3>
            <form action="{{ route('spd.pengajuan') }}" method="GET" class="flex flex-wrap gap-3 sm:gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                    <select name="departemen"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Departemen --</option>
                        @foreach ($departemenList as $dep)
                        <option value="{{ $dep->id }}" {{ request('departemen') == $dep->id ? 'selected' : '' }}>
                            {{ $dep->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit"
                        class="mt-5 text-sm px-4 py-2 border border-blue-500 text-blue-600 rounded-lg shadow-sm transition hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </form>

        </div>

        <!-- Card Daftar SPD -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Daftar SPD yang Diajukan</h3>
            </div>

            <!-- @if($spds->isEmpty())
            <p class="mb-3 text-gray-500 text-sm">Belum ada pengajuan SPD oleh Departemen HCM</p>
            @endif -->

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
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
                            <th class="py-3 px-4 text-sm font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($spds as $spd)

                        <tr class="border-b hover:bg-gray-50 transition duration-300">
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
                            <td class="py-3 px-4 text-sm">
                                <!-- Aksi berdasarkan status SPD -->
                                @if($spd->status == 'diajukan')
                                <a href="{{ route('spd.editStatus', $spd->id) }}" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                                    Persetujuan
                                </a>
                                @elseif($spd->status == 'disetujui')
                                <a href="{{ route('dpd.create', ['spd' => $spd->id]) }}" class="inline-block px-4 py-2 text-sm bg-green-500 text-white rounded-md hover:bg-green-600">
                                    Buat DPD
                                </a>
                                @elseif($spd->status == 'ditolak')
                                <span class="inline-block px-4 py-2 text-sm bg-gray-400 text-white rounded-md">
                                    Ditolak
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
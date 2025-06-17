<x-app-layout>


    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(auth()->user()->role === 'admindept_hcm')
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Deklarasi Perjalanan Dinas</h3>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Proses Penerbitan DPD:</strong></li>
                <ul class="list-disc list-inside ml-5">
                    <li>Pastikan SPD telah disetujui oleh pihak Finance </li>
                    <li>Pihak Finance menerbitkan satu DPD untuk satu SPD </li>
                    <li>DPD yang tampil adalah data akrual yang dikeluarkan oleh Finance</li>
                </ul>
                <li><strong>Tata Cara Rekap DPD:</strong></li>
                <ul class="list-disc list-inside ml-5">
                    <li>Anda bisa merekap data DPD setelah hasil filter ataupun tanpa filter </li>
                    <li>Mengekspor rekapan data ke format PDF / Spreadsheet </li>
                </ul>
            </ul>
        </div>
        @endif

        <div class="bg-white p-6 shadow-md rounded-lg mb-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4">Filter DPD</h3>
            <form action="{{ route('dpd.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                {{-- Filter Nama Pegawai --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pegawai</label>
                    <input type="text" name="nama_pegawai" value="{{ request('nama_pegawai') }}"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Cari nama pegawai...">
                </div>

                @if(auth()->user()->role === 'admindept_hcm' || auth()->user()->role === 'tmhcm')
                {{-- Filter Departemen --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                    <select name="departemen"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Departemen --</option>
                        @foreach ($departemenList as $dep)
                        <option value="{{ $dep->id }}" {{ request('departemen') == $dep->id ? 'selected' : '' }}>
                            {{ $dep->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Filter Tanggal Deklarasi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Deklarasi </label>
                    <input type="date" name="tanggal_deklarasi" value="{{ request('tanggal_deklarasi') }}"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>


                {{-- Filter Tanggal Dinas --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dinas (Berangkat) </label>
                    <input type="date" name="tgl_dinas_awal" value="{{ request('tgl_dinas_awal') }}"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dinas (Sampai)</label>
                    <input type="date" name="tgl_dinas_akhir" value="{{ request('tgl_dinas_akhir') }}"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <button type="submit"
                        class="mt-5 text-sm px-4 py-2 border border-blue-500 text-blue-600 rounded-lg shadow-sm transition hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>

                    <a href="{{ route('dpd.export-pdf', request()->query()) }}"
                        class="mt-5 text-sm px-4 py-2 border ml-4 border-red-500 text-red-600 rounded-lg shadow-sm transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-400">
                        <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                    </a>

                    <a href="{{ route('dpd.index') }}"
                        class="mt-5 text-sm px-4 py-2 ml-4 border border-blue-500 text-blue-600 rounded-lg shadow-sm transition hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <i class="fas fa-sync-alt mr-1"></i> Reload
                    </a>

                </div>
            </form>
        </div>

        <!-- Card Daftar DPD -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Daftar DPD</h3>
                <!-- <a href="{{ route('dpd.create') }}" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">Tambah DPD</a> -->
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="py-3 px-4 text-sm font-medium">Nomor SPD</th>
                            <th class="py-3 px-4 text-sm font-medium">Nama Pegawai</th>
                            <th class="py-3 px-4 text-sm font-medium">Tanggal Deklarasi</th>
                            <th class="py-3 px-4 text-sm font-medium">Total Biaya</th>
                            <th class="py-3 px-4 text-sm font-medium">Uraian</th>
                            <th class="py-3 px-4 text-sm font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deklarasi as $dpd)
                        <tr class="border-b hover:bg-gray-50 transition duration-300">
                            @if(auth()->user()->role === 'admindept_hcm' || auth()->user()->role === 'tmhcm')
                            <td class="py-3 px-4 text-sm">
                                <a href="{{ route('spd.index', ['nomor_spd' => $dpd->nomor_spd]) }}" class="text-blue-600 hover:underline">
                                    {{ $dpd->nomor_spd ?? 'N/A' }}
                                </a>
                                @elseif(auth()->user()->role === 'admindept')
                            <td class="py-3 px-4 text-sm">{{ $dpd->nomor_spd  ?? 'N/A' }}</td>
                            @endif
                            </td>
                            <td class="py-3 px-4 text-sm">{{ $dpd->nama_pegawai  ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($dpd->tanggal_deklarasi)->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-sm">Rp {{ number_format((float) $dpd->total_biaya, 1, ',', '.') }}</td>
                            <td class="py-3 px-4 text-sm">{{ $dpd->uraian ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-sm">
                                <a href="{{ route('dpd.show', $dpd->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Detail
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
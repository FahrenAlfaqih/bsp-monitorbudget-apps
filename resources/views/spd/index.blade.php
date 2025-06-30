<x-app-layout>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(auth()->user()->role === 'admindept_hcm')
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Pelaporan Surat Perjalanan Dinas</h3>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Tata Cara Pelaporan SPD:</strong></li>
                <ul class="list-disc list-inside ml-5">
                    <li>Pastikan SPD yang akan dilaporkan sudah tersedia di table, jika belum klik <strong>Tambah SPD</strong></li>
                    <li>Pilih list SPD karyawan yang akan dilaporkan ke finance (boleh lebih dari satu)</li>
                    <li>Klik <strong>Ajukan Laporan SPD ke Finance</strong> hingga status berubah menjadi <strong>diajukan</strong></li>
                    <li>Anda bisa melakukan Pelaporan SPD kembali jika status ditolak</li>
                    <li>SPD yang sudah diterima tidak dapat dilaporkan kembali</li>

                </ul>
                <li><strong>Status Pelaporan:</strong>
                    <ul class="list-disc list-inside ml-5">
                        <li>Secara default, status akan <strong>Menunggu</strong> selama anda belum mengajukan SPD</li>
                        <li>Jika status <strong>Ditolak</strong> maka akan menampilkan catatan dan tanggal deklarasi oleh Departemen Finance</li>
                        <li>Jika status <strong>Disetujui</strong> maka data SPD akan menerbitkan DPD</li>
                    </ul>
                </li>
            </ul>
        </div>
        @endif

        <div class="bg-white p-6 shadow-md rounded-lg mb-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4">Filter Pelaporan SPD</h3>
            <form action="{{ route('spd.index') }}" method="GET" class="flex flex-wrap gap-3 sm:gap-4 items-end">
                {{-- Filter Departemen --}}
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

                {{-- Filter Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Status --</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Filter Rentang Tanggal --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Filter Jenis Transport --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transport</label>
                    <select name="jenis_transport"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua jenis transport --</option>
                        <option value="darat" {{ request('jenis_transport') == 'darat' ? 'selected' : '' }}>Darat</option>
                        <option value="udara" {{ request('jenis_transport') == 'udara' ? 'selected' : '' }}>Udara</option>
                    </select>
                </div>


                <div>
                    <button type="submit" wa
                        class="mt-5 text-sm px-4 py-2 border border-blue-500 text-blue-600 rounded-lg shadow-sm transition hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>

                    <a href="{{ route('spd.export-pdf', request()->query()) }}"
                        class="mt-5 text-sm px-4 py-2 ml-4 border border-red-500 text-red-600 rounded-lg shadow-sm transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-400">
                        <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                    </a>


                    <a href="{{ route('spd.index') }}"
                        class="mt-5 text-sm px-4 py-2 ml-4 border border-blue-500 text-blue-600 rounded-lg shadow-sm transition hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <i class="fas fa-sync-alt mr-1"></i> Reload
                    </a>

                </div>
            </form>
        </div>

        <!-- Card Daftar SPD -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('spd.ajukan') }}" method="POST">
                @csrf
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-700">Daftar Rekapan Surat Perjalanan Dinas</h3>
                    <div class="flex gap-2 mb-4">
                        @if(auth()->user()->role === 'admindept_hcm')
                        <a href="{{ route('spd.create') }}" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">Rekap SPD Karyawan</a>
                        <button type="submit" class="px-6 py-2.5 text-white bg-green-600 hover:bg-green-700 font-medium text-sm rounded-lg shadow-md transition">
                            Ajukan Laporan SPD ke Finance
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-left border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600">
                                @if(auth()->user()->role === 'admindept_hcm')
                                <th class="py-3 px-4 text-sm font-medium">
                                    <input type="checkbox" id="checkAll" class="form-checkbox h-4 w-4 text-blue-600">
                                </th>
                                @endif
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
                                <th class="py-3 px-4 text-sm font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($spds as $spd)
                            <tr class="border-b hover:bg-gray-50 transition duration-300">
                                @if(auth()->user()->role === 'admindept_hcm')
                                <td class="py-3 px-4 text-sm">
                                    @if($spd->status === 'menunggu' || $spd->status === 'ditolak')
                                    <input type="checkbox" name="spd_ids[]" value="{{ $spd->id }}" class="form-checkbox h-4 w-4 text-blue-600">
                                    @endif
                                </td>
                                @endif
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
                                <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($spd->tanggal_deklarasi)->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <a href="{{ route('spd.show', $spd->id) }}" class="text-green-600 hover:text-green-800 font-medium">
                                        Detail
                                    </a>
                                    @if(auth()->user()->role === 'admindept_hcm')
                                    @if($spd->status !== 'disetujui' || $spd->status === 'diajukan')
                                    <a href="{{ route('spd.edit', $spd->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        Edit
                                    </a>
                                    @endif
                                    @endif

                                </td>

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
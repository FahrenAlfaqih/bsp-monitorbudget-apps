<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Rancangan Anggaran Biaya Perjalanan Dinas
            </h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Panduan Pengelolaan Rancangan Anggaran -->
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Pengelolaan Rancangan Anggaran</h3>

            @if(Auth::user()->role === 'admindept' || Auth::user()->role === 'admindept_hcm')
            <ul class="list-disc list-inside text-sm space-y-1">
                <li>Setelah diajukan, status berubah menjadi <strong>Menunggu</strong>.</li>
                <li>Anggaran akan ditinjau oleh Tim HCM dan Finance untuk disetujui atau ditolak.</li>
                <li>Jika <strong>disetujui</strong>, anggaran akan terkunci dan digunakan untuk program kerja selama 1 tahun.</li>
                <li>Jika <strong>ditolak</strong>, catatan evaluasi akan diberikan dan anggaran bisa diajukan kembali</li>
            </ul>

            @elseif(Auth::user()->role === 'tmhcm')
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Peninjauan:</strong> Tinjau setiap pengajuan anggaran dari departemen yang statusnya <strong>Menunggu</strong>.</li>
                <li><strong>Evaluasi:</strong> Pastikan anggaran sesuai dengan kebutuhan dan standar biaya perjalanan dinas.</li>
                <li><strong>Keputusan:</strong> Pilih <strong>Setujui</strong> jika anggaran layak, atau <strong>Tolak</strong> jika perlu revisi.</li>
                <li><strong>Finalisasi:</strong> Jika disetujui, sistem akan mengunci nilai anggaran untuk digunakan 1 tahun penuh.</li>
            </ul>

            @endif
        </div>

        <!-- Filter Periode -->
        <div class="bg-white p-6 shadow-md rounded-lg mb-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4">Filter Rancangan Anggaran</h3>

            <form action="{{ route('rancangan.index') }}" method="GET" class="flex flex-wrap gap-3 sm:gap-4 items-end">
                @if (Auth::user()->role === 'tmhcm')
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Status --</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <select name="periode"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Periode --</option>
                        @foreach ($periodeList as $periode)
                        <option value="{{ $periode->id }}" {{ request('periode') == $periode->id ? 'selected' : '' }}>
                            {{ $periode->nama_periode }}
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

        <div class="bg-white p-6 shadow-md rounded-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                <h3 class="font-semibold text-lg text-gray-800">
                    Rancangan Anggaran Departemen
                </h3>
            </div>
            @if($rancangan->isEmpty())
            @if (Auth::user()->role === 'admindept')
            <p class="mt-2 text-gray-500">Anda belum mengajukan rancangan anggaran apapun.</p>
            @else
            <p class="mt-2 text-gray-500"> Data pengajuan anggaran tidak tersedia .</p>
            @endif
            @else
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-separate border-spacing-0 mt-4">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="py-3 px-4 text-sm font-medium">Periode</th>
                            <th class="py-3 px-4 text-sm font-medium">Jumlah Anggaran</th>
                            <th class="py-3 px-4 text-sm font-medium">Departemen</th>
                            <th class="py-3 px-4 text-sm font-medium">Status</th>
                            <th class="py-3 px-4 text-sm font-medium">Catatan</th>
                            <th class="py-3 px-4 text-sm font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rancangan as $r)
                        <tr class="border-b hover:bg-gray-50 transition duration-300">
                            <td class="py-3 px-4 text-sm">{{ $r->periode->nama_periode }}</td>
                            <td class="py-3 px-4 text-sm">{{ formatRupiah($r->jumlah_anggaran) }}</td>
                            <td class="py-3 px-4 text-sm">{{ $r->departemen->nama ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-sm">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $r->status == 'menunggu' ? 'bg-yellow-100 text-yellow-800' : ($r->status == 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm">{{ $r->catatan ?? 'Tidak ada catatan' }}</td>
                            <td class="py-3 px-4 text-sm">
                                @if(auth()->user()->role === 'admindept' || auth()->user()->role === 'admindept_hcm')
                                @if($r->status === 'disetujui')
                                <button class="inline-block px-3 py-1 text-sm bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed" disabled>
                                    Edit
                                </button>
                                @else
                                <a href="{{ route('rancangan.edit', $r->id) }}"
                                    class="inline-block px-3 py-1 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg transition">
                                    Edit
                                </a>
                                @endif

                                @elseif(auth()->user()->role === 'tmhcm')
                                @if($r->status === 'disetujui')
                                <button class="inline-block px-3 py-1 text-sm bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed" disabled>
                                    Edit
                                </button>
                                @else
                                <a href="{{ route('rancangan.editStatus', $r->id) }}"
                                    class="inline-block px-3 py-1 text-sm text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition">
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
            @endif
        </div>

    </div>
</x-app-layout>
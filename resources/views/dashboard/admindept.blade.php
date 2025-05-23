<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                PT. Bumi Siak Pusako
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p id="welcomeText" class="mb-4"></p>
            <h3 class="text-lg font-semibold mt-4">Realisasi Anggaran Perjalanan Dinas </h3>

            <form method="GET" action="{{ route('dashboard.admindept') }}" class="mb-6 flex items-center gap-4">
                <label for="periode_id" class="text-sm font-medium text-gray-700">Filter Periode:</label>
                <select name="periode_id" id="periode_id"
                    class="border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Semua Periode --</option>
                    @foreach($semuaPeriode as $periode)
                    <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                        {{ $periode->nama_periode }}
                    </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow">
                    Tampilkan
                </button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">Total Anggaran Perjalanan Dinas</h4>
                    <p class="text-lg font-semibold text-green-600">Rp {{ number_format($periodeTerpilih->total_anggaran_disetujui, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">Total Pengeluaran</h4>
                    <p class="text-lg font-semibold text-red-600">Rp {{ number_format($periodeTerpilih->total_pengeluaran, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">Sisa Anggaran</h4>
                    <p class="text-lg font-semibold text-yellow-600">Rp {{ number_format($periodeTerpilih->sisa_anggaran, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                {{-- Top Karyawan --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-semibold text-black-700 mb-4">Top Karyawan dengan Biaya Dinas Tertinggi</h4>
                    <div class="text-sm text-gray-700 space-y-2">
                        @foreach($topKaryawan as $karyawan)
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $loop->iteration }}. {{ $karyawan->nama_pegawai }}</span>
                            <span class="text-gray-800 font-semibold">Rp {{ number_format($karyawan->total_biaya, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>


                {{-- Top Budget Departemen --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-semibold text-black-700 mb-4">Top Budget Departemen</h4>
                    <ul class="text-sm text-gray-700 space-y-2">
                        @foreach($topBudget as $budget)
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $loop->iteration }}. {{ $budget->nama_pegawai }}</span>
                            <span class="text-gray-800 font-semibold">Rp {{ number_format($budget->total_biaya, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </ul>
                </div>

                {{-- Tujuan Dinas Paling Sering --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-semibold text-black-700 mb-4">Tujuan Dinas Paling Sering</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach($topTujuanDinas as $tujuan)
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $loop->iteration }}. {{ $tujuan->tujuan }}</span>
                            <span class="text-gray-800 font-semibold">{{ $tujuan->jumlah_tujuan }} kali</span>
                        </div>
                        @endforeach
                    </ul>
                </div>
            </div>

            <h3 class="text-lg font-semibold mb-4">Periode Anggaran Aktif</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-bold mb-2">{{ $periodeTerpilih->nama_periode }}</h4>
                        <p class="text-sm text-gray-600">Mulai: {{ \Carbon\Carbon::parse($periodeTerpilih->mulai)->format('d M Y') }}</p>
                        <p class="text-sm text-gray-600">Berakhir: {{ \Carbon\Carbon::parse($periodeTerpilih->berakhir)->format('d M Y') }}</p>
                        @if ($periodeTerpilih->sudahMengajukan)
                        <div class="mt-2 text-sm">
                            @if ($periodeTerpilih->statusPengajuan === 'menunggu')
                            <p class="mt-2 text-sm text-gray-600">Status Pengajuan:
                                <span>
                                    Menunggu Persetujuan
                                </span>
                            </p>
                            @elseif ($periodeTerpilih->statusPengajuan === 'disetujui')
                            <p class="mt-2 text-sm text-gray-600">Status Pengajuan:
                                <span>
                                    Disetujui
                                </span>
                            </p>
                            @elseif ($periodeTerpilih->statusPengajuan === 'ditolak')
                            <p class="mt-2 text-sm text-gray-600">Status Pengajuan:
                                <span>
                                    Ditolak
                                </span>
                            </p>
                            @endif
                        </div>
                        @endif
                        <p class="mt-2 text-sm">
                            Status:
                            <span class="inline-block px-2 py-1 rounded-full text-white 
                {{ $periodeTerpilih->status === 'dibuka' ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ ucfirst($periodeTerpilih->status) }}
                            </span>
                        </p>


                    </div>

                    <div class="mt-4">
                        @if ($periodeTerpilih->status === 'ditutup')
                        <a href="{{ route('rancangan.index') }}"
                            class="inline-block w-full px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                            Lihat Pengajuan Anggaran
                        </a>
                        @else
                        @if ($periodeTerpilih->sudahMengajukan)
                        <a href="{{ route('rancangan.index') }}"
                            class="inline-block w-full px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                            Lihat Pengajuan Anggaran
                        </a>
                        @else
                        <a href="{{ route('rancangan.create', ['periode_id' => $periodeTerpilih->id]) }}"
                            class="inline-block w-full px-6 py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                            Ajukan Anggaran
                        </a>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        var typewriter = new Typewriter('#welcomeText', {
            loop: true,
            delay: 75,
        });

        typewriter
            .typeString('Selamat datang, Admin Departemen {{ auth()->user()->name }}!')
            .pauseFor(5000)
            .deleteAll()
            .start();
    </script>

    <script>
        const data = @json($data);
        console.log(data);
    </script>

</x-app-layout>
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
            <p id="welcomeText"></p>
            <div class="mt-3">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <!-- Statistik Card -->
                    <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                        <h4 class="text-lg font-bold mb-2">Total SPD</h4>
                        <p class="text-2xl font-semibold">{{ $totalSpd }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                        <h4 class="text-lg font-bold mb-2">Total DPD</h4>
                        <p class="text-2xl font-semibold">{{ $totalDpd }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                        <h4 class="text-lg font-bold mb-2">SPD yang Ditolak</h4>
                        <p class="text-2xl font-semibold text-red-600">{{ $spdDitolak }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                        <h4 class="text-lg font-bold mb-2">SPD yang Disetujui</h4>
                        <p class="text-2xl font-semibold text-green-600">{{ $spdDisetujui }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                        <h4 class="text-lg font-bold mb-2">SPD yang Diajukan</h4>
                        <p class="text-2xl font-semibold text-blue-600">{{ $spdDiajukan }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-lg font-semibold mb-4">Periode Anggaran Aktif</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($periodeAktif as $item)
                        <div class="bg-white rounded-xl shadow p-6 h-full flex flex-col justify-between">
                            <div>
                                <h4 class="text-lg font-bold mb-2">{{ $item->nama_periode }}</h4>
                                <p class="text-sm text-gray-600">Mulai: {{ \Carbon\Carbon::parse($item->mulai)->format('d M Y') }}</p>
                                <p class="text-sm text-gray-600">Berakhir: {{ \Carbon\Carbon::parse($item->berakhir)->format('d M Y') }}</p>
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
                        {{ $item->status === 'dibuka' ? 'bg-green-500' : 'bg-red-500' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </p>
                            </div>

                            <div class="mt-4">
                                @if ($item->sudahMengajukan)
                                <a href="{{ route('rancangan.index') }}"
                                    class="inline-block w-full px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                                    Lihat Pengajuan Anggaran
                                </a>
                                @else
                                <a href="{{ route('rancangan.create', ['periode_id' => $item->id]) }}"
                                    class="inline-block w-full px-6 py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                                    Ajukan Anggaran
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        @if($periodeAktif->isEmpty())
                        <div class="bg-white rounded-xl shadow p-6 text-center col-span-full">
                            <p class="text-gray-500">Tidak ada periode anggaran yang aktif untuk pengajuan.</p>
                        </div>
                        @endif
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
                .typeString('Selamat datang, {{ auth()->user()->name }}!')
                .pauseFor(5000)
                .deleteAll()
                .start();
        </script>

</x-app-layout>
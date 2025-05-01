<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            PT. Bumi Siak Pusako
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p id="welcomeText" class="mb-4"></p>


            
            {{-- Statistik Card --}}
            <h3 class="text-lg font-semibold mb-4">Anggaran Periode</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($periodeAktif as $item)
                <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">Total Anggaran Perjalanan Dinas</h4>
                    <p class="text-lg font-semibold text-green-600">Rp {{ number_format($item->total_anggaran_disetujui, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">Total Pengeluaran</h4>
                    <p class="text-lg font-semibold text-red-600">Rp {{ number_format($item->total_pengeluaran, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">Sisa Anggaran</h4>
                    <p class="text-lg font-semibold text-yellow-600">Rp {{ number_format($item->sisa_anggaran, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>

            {{-- Periode Anggaran Aktif --}}
            <h3 class="text-lg font-semibold mb-4">Periode Anggaran Aktif</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($periodeAktif as $item)
                <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-lg font-bold mb-2">{{ $item->nama_periode }}</h4>
                        <p class="text-sm text-gray-600">Mulai: {{ \Carbon\Carbon::parse($item->mulai)->format('d M Y') }}</p>
                        <p class="text-sm text-gray-600">Berakhir: {{ \Carbon\Carbon::parse($item->berakhir)->format('d M Y') }}</p>
                        <p class="mt-2 text-sm">
                            Status:
                            <span class="inline-block px-2 py-1 rounded-full text-white 
                                    {{ $item->status === 'dibuka' ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </p>
                    </div>

                    {{-- Action Buttons --}}
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

                {{-- No Active Period --}}
                @if($periodeAktif->isEmpty())
                <div class="bg-white rounded-xl shadow p-6 text-center col-span-full">
                    <p class="text-gray-500">Tidak ada periode anggaran yang aktif untuk pengajuan.</p>
                </div>
                @endif
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
            .pauseFor(5000) // jeda 10 detik (dalam milidetik)
            .deleteAll()
            .start();
    </script>
</x-app-layout>
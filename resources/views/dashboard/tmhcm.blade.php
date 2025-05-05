<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            PT. Bumi Siak Pusako
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p id="welcomeText" class="mb-4">Selamat datang, {{ auth()->user()->name }}</p>
            <form method="GET" action="{{ route('dashboard.tmhcm') }}" class="mb-6 flex items-center gap-4">
                <label for="periode_id" class="text-sm font-medium text-gray-700">Filter Periode:</label>
                <select name="periode_id" id="periode_id"
                    class="border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Semua Periode --</option>
                    @foreach($periodes as $periode)
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Card Karyawan -->



                <div class="bg-white shadow-xl rounded-2xl p-6">
                    <h2 class="text-xl font-bold mb-4 text-blue-600">Top 10 Pegawai</h2>
                    <ul class="space-y-2">
                        @foreach($topKaryawan as $karyawan)
                        <li class="flex items-center justify-between border-b pb-2 text-sm">
                            <span class="font-semibold w-1/3">{{ $karyawan->nama_pegawai }}</span>
                            <span class="text-gray-500 w-1/3">{{ $karyawan->nama_departemen }}</span>
                            <span class="text-gray-700 font-medium w-1/3 text-right">Rp {{ number_format($karyawan->total_biaya, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Card Departemen -->
                <div class="bg-white shadow-xl rounded-2xl p-6">
                    <h2 class="text-xl font-bold mb-4 text-green-600">Top 10 Departemen </h2>
                    <ul class="space-y-2">
                        @foreach($topDepartemen as $dept)
                        <li class="flex items-center justify-between border-b pb-2 text-sm">
                            <span class="w-2/3 font-semibold">{{ $dept->nama }}</span>
                            <span class="w-1/3 text-right text-gray-700 font-medium">
                                Rp {{ number_format($dept->total_biaya, 0, ',', '.') }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Card Transportasi -->
                <div class="bg-white shadow-xl rounded-2xl p-6">
                    <h2 class="text-xl font-bold mb-4 text-purple-600">Top 10 Transportasi </h2>
                    <ul class="space-y-2 text-sm">
                        @foreach($topTransportasi as $trans)
                        <li class="flex justify-between border-b pb-2 items-center">
                            <div class="w-2/3">
                                <div class="font-semibold">{{ $trans->jenis_transport }}</div>
                                <div class="text-gray-500 text-xs">{{ $trans->nama_transport }}</div>
                            </div>
                            <div class="w-1/3 text-right text-gray-700 font-medium">
                                Rp {{ number_format($trans->total_biaya, 0, ',', '.') }}
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4">Periode Anggaran Terbaru</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($periodes as $periode)
                    <div class="bg-white rounded-xl shadow p-6 h-full flex flex-col justify-between">
                        <div>
                            <h4 class="text-lg font-bold mb-2">{{ $periode->nama_periode }}</h4>
                            <p class="text-sm text-gray-600">Mulai: {{ \Carbon\Carbon::parse($periode->mulai)->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600">Berakhir: {{ \Carbon\Carbon::parse($periode->berakhir)->format('d M Y') }}</p>
                            <p class="mt-2 text-sm">
                                Status:
                                <span class="inline-block px-2 py-1 rounded-full text-white 
                        {{ $periode->status === 'dibuka' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($periode->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('periode.index') }}"
                                class="inline-block w-full px-6 py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                                Kelola Periode
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-xl shadow p-6 text-center col-span-full">
                        <p class="text-gray-500">Belum ada data periode anggaran.</p>
                    </div>
                    @endforelse
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
            .pauseFor(5000) // jeda 10 detik (dalam milidetik)
            .deleteAll()
            .start();
    </script>

</x-app-layout>
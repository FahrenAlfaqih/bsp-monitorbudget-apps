<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rancangan Anggaran Biaya Dinas
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(Auth::user()->role === 'tmhcm')
        <div class="bg-white p-2 shadow-lg rounded-lg mb-3">
            <form action="{{ route('rancangan.index') }}" method="GET" class="flex flex-wrap sm:flex-nowrap items-center justify-between gap-4 sm:gap-6 sm:mt-0">
                <div class="flex items-center gap-4 sm:gap-6 w-full sm:w-auto">
                    <div class="flex flex-col w-full sm:w-auto">
                        <label for="periodeSelect" class="text-sm font-semibold text-gray-700 mb-2">Filter Periode:</label>
                        <select id="periodeSelect" name="periode" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-lg shadow-sm px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out">
                            <option value="">Semua Periode</option>
                            @foreach($periodeList as $periode)
                            <option value="{{ $periode->id }}" {{ request('periode') == $periode->id ? 'selected' : '' }}>
                                {{ $periode->nama_periode }}
                            </option>
                            @endforeach
                        </select>
                    </div>  

                    <div class="flex flex-col w-full sm:w-auto">
                        <label for="departemenSelect" class="text-sm font-semibold text-gray-700 mb-2">Filter Departemen:</label>
                        <select id="departemenSelect" name="departemen" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-lg shadow-sm px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out">
                            <option value="">Semua Departemen</option>
                            @foreach($departemenList as $dept)
                            <option value="{{ $dept->id }}" {{ request('departemen') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </form>
        </div>


        @endif

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
                            <td class="py-3 px-4 text-sm">
                                @if(auth()->user()->role === 'admindept')
                                <a href="{{ route('rancangan.edit', $r->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                @elseif(auth()->user()->role === 'tmhcm')
                                <form action="{{ route('rancangan.updateStatus', $r->id) }}" method="POST" class="flex flex-col sm:flex-row items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs">
                                        <option value="menunggu" {{ $r->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="disetujui" {{ $r->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="ditolak" {{ $r->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">Update</button>
                                </form>
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
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Rancangan Anggaran Biaya Perjalanan Dinas
            </h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(Auth::user()->role === 'admindept')
        <!-- Panduan Pengisian Rancangan Anggaran hanya tampil untuk admin departemen -->
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h4 class="font-semibold  mb-2">Pengajuan Rancangan Anggaran</h4>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li>Tim Technical Manager HCM dan Finance akan meninjau anggaran yang diajukan untuk memastikan kecocokan dengan kebutuhan program kerja perjalanan dinas.</li>
                <li>Setelah rancangan anggaran disetujui, anggaran yang final akan digunakan untuk program kerja perjalanan dinas dan tidak dapat diubah selama periode satu tahun ke depan.</li>
                <li><strong>Status Anggaran:</strong>
                    <ul class="list-disc list-inside ml-5">
                        <li><strong>Menunggu:</strong> Anggaran sedang dilakukan peninjauan.</li>
                        <li><strong>Disetujui:</strong> Anggaran yang disetujui akan disimpan sebagai anggaran final dan tidak bisa diubah lagi. Anggaran ini digunakan untuk program kerja perjalanan dinas selama satu tahun ke depan.</li>
                        <li><strong>Ditolak:</strong> Anggaran yang ditolak harus diajukan ulang oleh Admin Departemen setelah melakukan revisi atau perubahan.</li>
                    </ul>
                </li>
            </ul>
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
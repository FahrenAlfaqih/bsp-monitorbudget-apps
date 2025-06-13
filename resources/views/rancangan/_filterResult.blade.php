<!-- Tabel untuk menampilkan data hasil filter -->
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
                                    {{ $r->status == 'menunggu' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($r->status == 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
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
                                    Persetujuan
                                </button>
                            @else
                                <a href="{{ route('rancangan.editStatus', $r->id) }}"
                                   class="inline-block px-3 py-1 text-sm text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition">
                                    Persetujuan
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

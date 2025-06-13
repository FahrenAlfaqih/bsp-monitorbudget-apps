<tbody>
    @foreach ($deklarasi as $dpd)
        <tr class="border-b hover:bg-gray-50 transition duration-300">
            @if(auth()->user()->role === 'admindept_hcm' || auth()->user()->role === 'tmhcm')
                <td class="py-3 px-4 text-sm">
                    <a href="{{ route('spd.index', ['nomor_spd' => $dpd->nomor_spd]) }}" class="text-blue-600 hover:underline">
                        {{ $dpd->nomor_spd ?? 'N/A' }}
                    </a>
                </td>
            @elseif(auth()->user()->role === 'admindept')
                <td class="py-3 px-4 text-sm">{{ $dpd->nomor_spd ?? 'N/A' }}</td>
            @endif
            <td class="py-3 px-4 text-sm">{{ $dpd->nama_pegawai ?? 'N/A' }}</td>
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

<x-app-layout>


    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">

            <h3 class="text-lg font-semibold mb-4">Informasi SPD</h3>
            <table class="min-w-full border border-gray-300 text-sm mb-6">
                <tbody>
                    <tr class="border-b">
                        <th class="border px-4 py-2 text-left w-1/3">Nomor SPD</th>
                        <td class="border px-4 py-2">{{ $spd->nomor_spd }}</td>
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <th class="border px-4 py-2 text-left">Nama Pegawai</th>
                        <td class="border px-4 py-2">{{ $spd->nama_pegawai }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="border px-4 py-2 text-left">Departemen</th>
                        <td class="border px-4 py-2">{{ $spd->departemen->nama ?? 'N/A' }}</td>
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <th class="border px-4 py-2 text-left">Asal</th>
                        <td class="border px-4 py-2">{{ $spd->asal }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="border px-4 py-2 text-left">Tujuan</th>
                        <td class="border px-4 py-2">{{ $spd->tujuan }}</td>
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <th class="border px-4 py-2 text-left">Kegiatan</th>
                        <td class="border px-4 py-2">{{ $spd->kegiatan }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="border px-4 py-2 text-left">Tanggal Berangkat</th>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($spd->tanggal_berangkat)->format('d M Y') }}</td>
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <th class="border px-4 py-2 text-left">Tanggal Kembali</th>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($spd->tanggal_kembali)->format('d M Y') }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="border px-4 py-2 text-left">Jenis Transport</th>
                        <td class="border px-4 py-2">{{ $spd->jenis_transport }}</td>
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <th class="border px-4 py-2 text-left">Nama Transport</th>
                        <td class="border px-4 py-2">{{ $spd->nama_transport }}</td>
                    </tr>
                    <tr class="border-b">
                        <th class="border px-4 py-2 text-left">Status</th>
                        <td class="border px-4 py-2">{{ ucfirst($spd->status) }}</td>
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <th class="border px-4 py-2 text-left">Tanggal Deklarasi</th>
                        <td class="border px-4 py-2">{{ $spd->tanggal_deklarasi ? \Carbon\Carbon::parse($spd->tanggal_deklarasi)->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="border px-4 py-2 text-left">Catatan</th>
                        <td class="border px-4 py-2">{{ $spd->uraian ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <h3 class="text-lg font-semibold mb-4">Rincian Biaya Perjalanan Dinas</h3>
            <table class="min-w-full border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-left">Jenis Biaya</th>
                        <th class="border px-4 py-2 text-left">Keterangan</th>
                        <th class="border px-4 py-2 text-right">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalNominal = 0; @endphp
                    @forelse($spd->details as $detail)
                    @php
                    $nominalFloat = (float) $detail->nominal;
                    $totalNominal += $nominalFloat;
                    @endphp
                    <tr>
                        <td class="border px-4 py-2">{{ $detail->jenis_biaya }}</td>
                        <td class="border px-4 py-2">{{ $detail->keterangan ?? '-' }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($nominalFloat, 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="border px-4 py-2 text-center text-gray-500">Belum ada rincian biaya</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-200 font-semibold">
                        <td colspan="2" class="border px-4 py-2 text-right">Total</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($totalNominal, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-6">
                <a href="{{ route('spd.index') }}" class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">Kembali ke SPD</a>
            </div>
        </div>
    </div>
</x-app-layout>
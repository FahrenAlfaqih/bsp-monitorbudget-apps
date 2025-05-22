<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Deklarasi Perjalanan Dinas - {{ $dpd->spd->nomor_spd }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4">Informasi SPD</h3>
            <table class="min-w-full border border-gray-300 mb-6">
                <tbody>
                    <tr>
                        <td class="border px-4 py-2 font-semibold">Nomor SPD</td>
                        <td class="border px-4 py-2">{{ $dpd->spd->nomor_spd }}</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2 font-semibold">Nama Pegawai</td>
                        <td class="border px-4 py-2">{{ $dpd->spd->nama_pegawai }}</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2 font-semibold">Departemen</td>
                        <td class="border px-4 py-2">{{ $dpd->spd->departemen->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2 font-semibold">Tanggal Berangkat</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($dpd->spd->tanggal_berangkat)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2 font-semibold">Tanggal Kembali</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($dpd->spd->tanggal_kembali)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2 font-semibold">Jenis Transport</td>
                        <td class="border px-4 py-2">{{ $dpd->spd->jenis_transport }}</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2 font-semibold">Nama Transport</td>
                        <td class="border px-4 py-2">{{ $dpd->spd->nama_transport }}</td>
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
                    @forelse($dpd->spd->details as $detail)
                    @php
                    $nominalFloat = (float) $detail->nominal;
                    $totalNominal += $nominalFloat;
                    @endphp <tr>
                        <td class="border px-4 py-2">{{ $detail->jenis_biaya }}</td>
                        <td class="border px-4 py-2">{{ $detail->keterangan ?? '-' }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($nominalFloat, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-200 font-semibold">
                        <td colspan="2" class="border px-4 py-2 text-right">Total</td>
                        <td class="border px-4 py-2 text-right">Rp {{ number_format($totalNominal, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-app-layout>
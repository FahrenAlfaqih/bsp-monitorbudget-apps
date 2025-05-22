<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Surat Perjalanan Dinas Karyawan
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
                <h3 class="font-semibold mb-2">Panduan Edit Surat Perjalanan Dinas</h3>
                <ul class="list-disc list-inside text-sm space-y-1">
                    <li><strong>Hal yang perlu diperhatikan:</strong> Pastikan mengedit data SPD sesuai dengan informasi yang baru, terutama rincian biaya perjalanan.</li>
                    <li><strong>Tata Cara Edit SPD:</strong> Semua data akan disesuaikan dengan yang terbaru, pastikan tidak ada kesalahan pada rincian biaya.</li>
                </ul>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <form action="{{ route('spd.update', $spd->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-select name="departemen_id" label="Departemen" :options="$departemen->pluck('nama', 'id')" :selected="$spd->departemen_id" required />
                        <x-input name="periode_nama" label="Periode" :value="$spd->periode->nama_periode ?? ''" readonly />
                        <input type="hidden" name="periode_id" value="{{ $spd->periode_id ?? '' }}">
                        <x-input name="nomor_spd" label="Nomor SPD" value="{{ old('nomor_spd', $spd->nomor_spd) }}" required />
                        <x-select name="pegawai_id" label="Nama Pegawai" :options="$pegawais->pluck('nama_pegawai', 'id')" :selected="$spd->pegawai_id" required class="select2" />
                        <x-input name="asal" label="Asal Dinas" value="{{ old('asal', $spd->asal) }}" required />
                        <x-input name="tujuan" label="Tujuan Dinas" value="{{ old('tujuan', $spd->tujuan) }}" required />
                        <x-input name="tanggal_berangkat" label="Tanggal Berangkat" type="date" value="{{ old('tanggal_berangkat', $spd->tanggal_berangkat) }}" required />
                        <x-input name="tanggal_kembali" label="Tanggal Kembali" type="date" value="{{ old('tanggal_kembali', $spd->tanggal_kembali) }}" required />
                        <x-select name="jenis_transport" label="Jenis Transport" :options="['Udara' => 'Udara', 'Darat' => 'Darat']" :selected="$spd->jenis_transport" required />
                        <x-input name="nama_transport" label="Nama Transportasi" value="{{ old('nama_transport', $spd->nama_transport) }}" required />
                        <x-textarea name="kegiatan" label="Kegiatan" rows="4" value="{{ old('kegiatan', $spd->kegiatan) }}" required />
                    </div>

                    <h3 class="font-semibold mb-2 mt-8">Rincian Biaya Perjalanan Dinas</h3>
                    <div id="biayaContainer" class="space-y-4">
                        @forelse($spd->details as $index => $detail)
                        <div class="grid grid-cols-3 gap-4 items-end biaya-item">
                            <div>
                                <label for="jenis_biaya_{{ $index }}" class="block text-sm font-medium text-gray-700">Jenis Biaya</label>
                                <input
                                    type="text"
                                    name="details[{{ $index }}][jenis_biaya]"
                                    id="jenis_biaya_{{ $index }}"
                                    value="{{ old('details.' . $index . '.jenis_biaya', $detail->jenis_biaya) }}"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    placeholder="Contoh: Transportasi"
                                />
                            </div>
                            <div>
                                <label for="nominal_{{ $index }}_display" class="block text-sm font-medium text-gray-700">Nominal</label>
                                <input
                                    type="text"
                                    id="nominal_{{ $index }}_display"
                                    class="rupiah-input mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    value="{{ old('details.' . $index . '.nominal', 'Rp. ' . number_format(parseRupiahToFloat($detail->nominal), 2, ',', '.')) }}"
                                />
                                <input
                                    type="hidden"
                                    name="details[{{ $index }}][nominal]"
                                    id="nominal_{{ $index }}"
                                    value="{{ old('details.' . $index . '.nominal', $detail->nominal) }}"
                                />
                            </div>
                            <div>
                                <label for="keterangan_{{ $index }}" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <input
                                    type="text"
                                    name="details[{{ $index }}][keterangan]"
                                    id="keterangan_{{ $index }}"
                                    value="{{ old('details.' . $index . '.keterangan', $detail->keterangan) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    placeholder="Opsional"
                                />
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500">Belum ada rincian biaya.</p>
                        @endforelse
                    </div>

                    <button type="button" id="addDetailBtn" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                        Tambah Rincian Biaya
                    </button>

                    <div class="flex justify-end mt-8">
                        <a href="{{ route('spd.index') }}" class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">Batal</a>
                        <button type="submit" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">Simpan Rekapan SPD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Cari pegawai...",
                allowClear: true
            });
        });

        $(document).ready(function() {
            let index = @json($spd->details->count());

            function formatRupiah(value) {
                value = value.replace(/[^,\d]/g, '').toString();
                let split = value.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }

            function onInputNominal(e) {
                let input = e.target;
                let cleanVal = input.value;

                let numericVal = cleanVal.replace(/[^,\d]/g, '');

                let formatted = formatRupiah(numericVal);
                input.value = 'Rp. ' + formatted;

                let idx = input.id.match(/\d+/)[0];
                $('#nominal_' + idx).val(formatted.replace(/\./g, '').replace(',', '.'));
            }

            function attachRupiahListeners() {
                $('.rupiah-input').off('input').on('input', onInputNominal);
            }

            attachRupiahListeners();

            $('#addDetailBtn').click(function() {
                let html = `
                    <div class="grid grid-cols-3 gap-4 items-end biaya-item mt-3">
                        <div>
                            <label for="jenis_biaya_${index}" class="block text-sm font-medium text-gray-700">Jenis Biaya</label>
                            <input type="text" name="details[${index}][jenis_biaya]" id="jenis_biaya_${index}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Konsumsi" />
                        </div>
                        <div>
                            <label for="nominal_${index}_display" class="block text-sm font-medium text-gray-700">Nominal</label>
                            <input type="text" name="dummy_nominal_${index}" id="nominal_${index}_display" required class="rupiah-input mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Rp. 0" />
                            <input type="hidden" name="details[${index}][nominal]" id="nominal_${index}" />
                        </div>
                        <div>
                            <label for="keterangan_${index}" class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <input type="text" name="details[${index}][keterangan]" id="keterangan_${index}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Opsional" />
                        </div>
                    </div>
                `;
                $('#biayaContainer').append(html);
                attachRupiahListeners();
                index++;
            });
        });
    </script>
    @endpush
</x-app-layout>

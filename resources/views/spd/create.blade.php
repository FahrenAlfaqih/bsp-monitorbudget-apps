<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Surat Perjalanan Dinas
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
                <h3 class="font-semibold mb-2">Panduan Penyimpanan Surat Perjalanan Dinas</h3>
                <ul class="list-disc list-inside text-sm space-y-1">
                    <li><strong>Hal yang perlu diperhatikan:</strong> Formulir tambah SPD ini bertujuan untuk menambahkan data SPD baru dari karyawan yang<strong> telah melaksanakan perjalanan dinas</strong> dan digunakan nantinya untuk pengajuan SPD ke pihak Finance</li>
                    </li>
                    <li><strong>Tata Cara Penambahan SPD:</strong></li>
                    <ul class="list-disc list-inside ml-5">
                        <li><strong>Departemen:</strong> Pilih departemen yang melaksanakan perjalanan dinas. Pastikan data departemen sudah tersedia di sistem.</li>
                        <li><strong>Periode:</strong> Periode akan otomatis terisi dengan periode anggaran perjalanan dinas sekarang</li>
                        <li><strong>Nomor SPD:</strong> Masukkan nomor dokumen SPD.</li>
                        <li><strong>Nama Pegawai:</strong> Pilih nama pegawai yang melaksanakan perjalanan dinas. Data ini diambil dari daftar pegawai yang sudah ada.</li>
                        <li><strong>Asal Dinas:</strong> Tuliskan lokasi keberangkatan pegawai, contohnya "Zamrud Home Base".</li>
                        <li><strong>Tujuan Dinas:</strong> Isi dengan lokasi tujuan perjalanan dinas, misalnya "Jakarta".</li>
                        <li><strong>Tanggal Berangkat dan Tanggal Kembali:</strong> Masukkan tanggal keberangkatan dan tanggal pulang sesuai dokumen SPD . Gunakan format <span class="font-mono">mm-dd-yyyy</span> dan pastikan tanggal kembali tidak lebih awal dari tanggal berangkat.</li>
                        <li><strong>Jenis Transport:</strong> Pilih jenis transportasi yang digunakan, apakah melalui "Udara" atau "Darat".</li>
                        <li><strong>Nama Transportasi:</strong> Isi nama maskapai atau kendaraan, contohnya "Garuda Indonesia".</li>
                        <li><strong>Kegiatan:</strong> Jelaskan secara singkat dan jelas tujuan kegiatan dinas, misalnya "Rapat koordinasi dengan mitra proyek di Jakarta".</li>
                    </ul>

            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <form action="{{ route('spd.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-select name="departemen_id" label="Departemen" :options="$departemen->pluck('nama', 'id')" :selected="request('departemen')" required />
                        <x-input name="periode_nama" label="Periode" :value="$periodes->nama_periode" readonly />
                        <input type="hidden" name="periode_id" value="{{ $periodes->id }}"> <x-input name="nomor_spd" label="Nomor SPD" required />
                        <x-select name="pegawai_id" label="Nama Pegawai" :options="$pegawais->pluck('nama_pegawai', 'id')" required class="select2" />
                        <x-input name="asal" label="Asal Dinas" required />
                        <x-input name="tujuan" label="Tujuan Dinas" required />
                        <x-input name="tanggal_berangkat" label="Tanggal Berangkat" type="date" required />
                        <x-input name="tanggal_kembali" label="Tanggal Kembali" type="date" required />
                        <x-select name="jenis_transport" label="Jenis Transport" :options="['Udara' => 'Udara', 'Darat' => 'Darat']" required />
                        <x-input name="nama_transport" label="Nama Transportasi" required />
                        <x-textarea name="kegiatan" label="Kegiatan" rows="4" required />
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end mt-8">
                        <a href="{{ route('spd.index') }}"
                            class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                            Simpan SPD
                        </button>
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
    </script>
    @endpush

</x-app-layout>
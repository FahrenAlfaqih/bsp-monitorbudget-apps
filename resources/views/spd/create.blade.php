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
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <form action="{{ route('spd.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-select name="departemen_id" label="Departemen" :options="$departemen->pluck('nama', 'id')" :selected="request('departemen')" required />
                        <x-select name="periode_id" label="Periode" :options="$periodes->pluck('nama_periode', 'id')" required />
                        <x-input name="nomor_spd" label="Nomor SPD" required />
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
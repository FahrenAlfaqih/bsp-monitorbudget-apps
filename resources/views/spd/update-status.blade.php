<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Update Status Surat Perjalanan Dinas
            </h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Edit Pengajuan Surat Perjalanan Dinas</h3>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Tata Cara Pengajuan SPD:</strong></li>
                <ul class="list-disc list-inside ml-5">
                    <li>Pastikan anda telah benar benar mereview dokumen SPD yang telah diajukan</li>
                    <li>Pilih status dari SPD yang diajukan</li>
                    <li>Status SPD yang telah <strong>Disetujui</strong> tidak akan dapat diubah kembali</li>
                    <li>SPD yang disetujui akan menerbitkan data Deklarasi Perjalanan Dinas (DPD)</li>
                </ul>
                <li><strong>Status Pengajuan:</strong>
                    <ul class="list-disc list-inside ml-5">
                        <li>Jika status <strong>Disetujui</strong> maka anda wajib mengisi total biaya, tanggal deklarasi, dan uraian (catatan wajib)</li>
                        <li>Jika status <strong>Ditolak</strong> maka anda cukup mengisi tanggal deklarasi dan uraian (catatan wajib)</li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Form Update Status SPD</h3>

            <form action="{{ route('spd.updateStatus', $spd->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6 mb-6">

                    {{-- Status --}}
                    <x-select
                        name="status"
                        label="Status"
                        :options="['disetujui' => 'Disetujui', 'ditolak' => 'Ditolak']"
                        :selected="$spd->status"
                        required
                        id="status" />

                    {{-- Total Biaya --}}
                    <div id="biayaGroup">
                        <x-input
                            name="total_biaya_display"
                            label="Total Biaya"
                            value="{{ old('total_biaya') }}"
                            id="total_biaya_display" />
                        <input type="hidden" name="total_biaya" id="total_biaya">
                        @error('total_biaya')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Uraian --}}
                    <x-textarea
                        name="uraian"
                        label="Uraian"
                        rows="4"
                        required
                        :value="old('uraian')" />


                    {{-- PR --}}
                    <x-input
                        name="pr"
                        label="Nomor Purchase Request (PR)"
                        :value="old('pr')"
                        required />

                    {{-- PO --}}
                    <x-input
                        name="po"
                        label="Nomor Purchase Order (PO)"
                        type="date"
                        :value="old('po')"
                        required />

                    {{-- SES --}}
                    <x-input
                        name="ses"
                        label="Nomor Service Entry Sheet (SES)"
                        :value="old('ses')"
                        required />

                    {{-- Tanggal Deklarasi --}}
                    <x-input
                        name="tanggal_deklarasi"
                        label="Tanggal Deklarasi"
                        type="date"
                        :value="old('tanggal_deklarasi')"
                        required />

                    {{-- Uraian --}}
                    <x-textarea
                        name="uraian"
                        label="Uraian"
                        rows="4"
                        required
                        :value="old('uraian')" />
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end mt-8">
                    <a href="{{ route('spd.pengajuan') }}"
                        class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const statusSelect = document.getElementById('status');
        const biayaGroup = document.getElementById('biayaGroup');
        const displayInput = document.getElementById('total_biaya_display');
        const hiddenInput = document.getElementById('total_biaya');

        function handleStatusChange() {
            if (statusSelect.value === 'ditolak') {
                biayaGroup.style.display = 'none';
                displayInput.value = '';
                hiddenInput.value = '';
            } else {
                biayaGroup.style.display = 'block';
            }
        }

        displayInput.addEventListener('input', function(e) {
            let value = this.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            this.value = 'Rp. ' + rupiah;

            let cleanValue = value.replace(/\./g, '');
            hiddenInput.value = cleanValue;
        });

        statusSelect.addEventListener('change', handleStatusChange);
        window.addEventListener('DOMContentLoaded', handleStatusChange);
    </script>
</x-app-layout>
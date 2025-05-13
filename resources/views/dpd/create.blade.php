<!-- resources/views/dpd/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Deklarasi Perjalanan Dinas
            </h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Card Form DPD -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Form Deklarasi Perjalanan Dinas</h3>
            <form method="POST" action="{{ route('dpd.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-6 mb-6">
                    <!-- SPD -->
                    <div>
                        <label for="spd_id" class="block text-sm font-medium text-gray-700">Nomor SPD</label>
                        <select name="spd_id" id="spd_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih SPD</option>
                            @foreach ($spds as $spd)
                            <option value="{{ $spd->id }}" {{ old('spd_id') == $spd->id ? 'selected' : '' }}>
                                {{ $spd->nomor_spd }} - {{ $spd->nama_pegawai }}
                            </option>
                            @endforeach
                        </select>
                        @error('spd_id')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Total Biaya -->
                    <div>
                        <label for="total_biaya" class="block text-sm font-medium text-gray-700">Total Biaya</label>
                        <input type="text" id="total_biaya_display" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('total_biaya') }}">
                        <input type="hidden" name="total_biaya" id="total_biaya">
                        @error('total_biaya')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tanggal Deklarasi -->
                    <div>
                        <label for="tanggal_deklarasi" class="block text-sm font-medium text-gray-700">Tanggal Deklarasi</label>
                        <input type="date" name="tanggal_deklarasi" id="tanggal_deklarasi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('tanggal_deklarasi') }}">
                        @error('tanggal_deklarasi')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Uraian -->
                    <div>
                        <label for="uraian" class="block text-sm font-medium text-gray-700">Uraian</label>
                        <textarea name="uraian" id="uraian" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('uraian') }}</textarea>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end mt-8">
                    <a href="{{ route('dpd.index') }}"
                        class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                        Simpan DPD
                    </button>
                </div>


            </form>
        </div>
    </div>

    <script>
        const displayInput = document.getElementById('total_biaya_display');
        const hiddenInput = document.getElementById('total_biaya');

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
    </script>
</x-app-layout>
<x-app-layout>


    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Panduan Pengisian --}}
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Pengisian Rancangan Anggaran</h3>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Periode:</strong> Sistem akan memilih otomatis berdasarkan periode aktif.</li>
                <li><strong>Jumlah Anggaran:</strong> Isi angka total anggaran yang diajukan (misalnya: 15000000).</li>
                <li>Format input akan otomatis dikonversi ke format Rupiah saat mengetik.</li>
                <li>Pastikan anggaran yang diajukan sesuai dengan kebutuhan dan realitas perjalanan dinas.</li>
            </ul>
        </div>

        <form action="{{ route('rancangan.update', $rancangan->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-sm">
            @csrf
            @method('PUT')

            {{-- Periode (disabled select + hidden input) --}}
            <div class="mb-4">
                <label for="periode_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Periode
                </label>
                <select disabled id="periode_id"
                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach ($periode as $item)
                        <option value="{{ $item->id }}"
                            {{ (old('periode_id', $rancangan->periode_id) == $item->id) ? 'selected' : '' }}>
                            {{ $item->nama_periode }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="periode_id" value="{{ $rancangan->periode_id }}">
            </div>

            {{-- Jumlah Anggaran --}}
            <div class="mb-4">
                <x-input name="jumlah_anggaran_display" label="Jumlah Anggaran" id="jumlah_anggaran_display" required value="{{ old('jumlah_anggaran_display', number_format($rancangan->jumlah_anggaran, 0, ',', '.')) }}" />
                <input type="hidden" name="jumlah_anggaran" id="jumlah_anggaran" value="{{ old('jumlah_anggaran', $rancangan->jumlah_anggaran) }}">
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end mt-8">
                <a href="{{ route('rancangan.index') }}"
                    class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        const displayInput = document.getElementById('jumlah_anggaran_display');
        const hiddenInput = document.getElementById('jumlah_anggaran');

        displayInput.addEventListener('input', function (e) {
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

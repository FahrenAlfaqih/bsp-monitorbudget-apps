<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajukan Rancangan Anggaran
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-xl p-8">
                <form action="{{ route('rancangan.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="periode_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Periode
                        </label>
                        <select disabled id="periode_id"
                            class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($periode as $item)
                            <option value="{{ $item->id }}" {{ (old('periode_id', $selectedPeriodeId) == $item->id) ? 'selected' : '' }}>
                                {{ $item->nama_periode }}
                            </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="periode_id" value="{{ $selectedPeriodeId }}">


                    </div>

                    <div>
                        <label for="jumlah_anggaran" class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Anggaran
                        </label>
                        <input type="text" id="jumlah_anggaran_display" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required />

                        <input type="hidden" name="jumlah_anggaran" id="jumlah_anggaran">
                    </div>


                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition transform hover:scale-105 duration-200">
                            Ajukan Anggaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const displayInput = document.getElementById('jumlah_anggaran_display');
        const hiddenInput = document.getElementById('jumlah_anggaran');

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
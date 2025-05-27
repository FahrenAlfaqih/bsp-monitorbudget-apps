<x-app-layout>


    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Panduan Pengisian --}}
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Pengisian Periode Anggaran</h3>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Nama Periode:</strong> Masukkan nama yang jelas, misalnya "Periode Anggaran 2025".</li>
                <li><strong>Tanggal Mulai Pengajuan dan Tanggal Berakhir Pengajuan:</strong> Isi menggunakan format <span class="font-mono">mm/dd/yyyy</span> (bulan/hari/tahun).</li>
                <li>Pastikan tanggal mulai pengajuan lebih awal dari tanggal berakhir pengajuan .</li>
                <li>Status periode anggaran akan berubah otomatis menjadi "Ditutup" ketika tanggal berakhir sudah melewati tanggal sekarang.</li>
            </ul>
        </div>

        <form action="{{ route('periode.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-sm">
            @csrf

            <div class="mb-4">
                <x-input name="nama_periode" label="Nama Periode" required />
            </div>

            <div class="mb-4">
                <x-input name="mulai" label="Tanggal Mulai Pengajuan" type="date" required />
            </div>

            <div class="mb-4">
                <x-input name="berakhir" label="Tanggal Berakhir Pengajuan" type="date" required />
            </div>

            <div class="flex justify-end mt-8">
                <a href="{{ route('periode.index') }}"
                    class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                    Simpan Periode
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
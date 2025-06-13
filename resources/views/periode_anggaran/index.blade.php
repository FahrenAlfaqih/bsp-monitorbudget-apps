<x-app-layout>


    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Penggunaan Periode Pengajuan Anggaran</h3>
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Fungsi Periode Pengajuan:</strong> Sebagai wadah untuk departemen mengajukan anggaran perjalanan dinas untuk satu tahun ke depan.</li>
                <li><strong>Status Periode:</strong>
                    <ul class="list-disc list-inside ml-5">
                        <li>Secara default, status akan <strong>Dibuka</strong> selama tanggal berakhir belum lewat dari tanggal sekarang.</li>
                        <li>Jika tanggal berakhir melewati tanggal sekarang, status otomatis akan berubah menjadi <strong>Ditutup</strong>, dan departemen tidak dapat lagi mengajukan anggaran.</li>
                    </ul>
                </li>
                <li><strong>Ketentuan:</strong> Hanya boleh membuat <strong>satu periode anggaran</strong> dalam satu tahun. Pastikan tidak ada duplikasi tahun.</li>
            </ul>
        </div>


        <!-- Card Periode Anggaran -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Periode Anggaran</h3>
                <a href="{{ route('periode.create') }}" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">Tambah Periode Pengajuan</a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="py-3 px-4 text-sm font-medium">Nama Periode</th>
                            <th class="py-3 px-4 text-sm font-medium">Tanggal Mulai Pengajuan</th>
                            <th class="py-3 px-4 text-sm font-medium">Tanggal Berakhir Pengajuan</th>
                            <th class="py-3 px-4 text-sm font-medium">Status</th>
                            <th class="py-3 px-4 text-sm font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($periodes as $periode)
                        <tr class="border-b hover:bg-gray-50 transition duration-300">
                            <td class="py-3 px-4 text-sm">{{ $periode->nama_periode }}</td>
                            <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($periode->mulai)->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($periode->berakhir)->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-sm text-center">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $periode->status == 'dibuka' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($periode->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm">
                                <form method="POST" action="{{ route('periode.edit.post') }}">
                                    @csrf
                                    <input type="hidden" name="periode_id" value="{{ $periode->id }}">
                                    <button type="submit" class="inline-block px-3 py-1 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg transition">
                                        Edit
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>


    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Pegawai</h3>
                <a href="{{ route('pegawai.create') }}" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">Tambah Pegawai</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="py-3 px-4 text-sm font-medium">No. Pekerja</th>
                            <th class="py-3 px-4 text-sm font-medium">Nama Pegawai</th>
                            <th class="py-3 px-4 text-sm font-medium">Email</th>
                            <th class="py-3 px-4 text-sm font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pegawais as $pegawai)
                        <tr class="border-b hover:bg-gray-50 transition duration-300">
                            <td class="py-3 px-4 text-sm">{{ $pegawai->nopekerja }}</td>
                            <td class="py-3 px-4 text-sm">{{ $pegawai->nama_pegawai }}</td>
                            <td class="py-3 px-4 text-sm">{{ $pegawai->email }}</td>
                            <td class="py-3 px-4 text-sm">
                                <a href="{{ route('pegawai.edit', $pegawai->id) }}"
                                    class="inline-block px-3 py-1 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg transition">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
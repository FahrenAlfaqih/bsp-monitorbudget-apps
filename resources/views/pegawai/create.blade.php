<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Pegawai
            </h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Panduan Pengisian --}}
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Penambahan Pegawai</h3>
            <ul class="list-disc list-inside text-sm space-y-1 ml-5">
                <li><strong>Nomor Pekerja:</strong> Masukkan nomor pekerja unik yang belum pernah digunakan.</li>
                <li><strong>Nama Pegawai:</strong> Isikan nama lengkap pegawai sesuai data resmi.</li>
                <li><strong>Email:</strong> Gunakan alamat email valid yang belum terdaftar di sistem.</li>
                <li class="text-blue-600"><strong>Catatan:</strong> Semua data wajib diisi untuk memastikan data pegawai lengkap dan valid.</li>

            </ul>
        </div>

        <div class="bg-white p-8 shadow-lg rounded-2xl">
            <form action="{{ route('pegawai.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Nomor Pekerja --}}
                <x-input name="nopekerja" label="Nomor Pekerja" required />

                {{-- Nama Pegawai --}}
                <x-input name="nama_pegawai" label="Nama Pegawai" required />

                {{-- Email --}}
                <x-input name="email" label="Email" type="email" required />

                {{-- Tombol --}}
                <div class="flex justify-end mt-8">
                    <a href="{{ route('pegawai.index') }}"
                        class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                        Simpan Pegawai
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Pegawai
            </h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Panduan Pengisian --}}
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Pengeditan Pegawai</h3>
            <ul class="list-disc list-inside text-sm space-y-1 ml-5">
                <li><strong>Nomor Pekerja:</strong> Pastikan nomor pekerja unik dan tidak sama dengan pegawai lain.</li>
                <li><strong>Nama Pegawai:</strong> Edit nama lengkap sesuai data resmi.</li>
                <li><strong>Email:</strong> Gunakan alamat email valid dan aktif.</li>
                <li><strong>Catatan:</strong> Semua data wajib diisi dengan benar untuk kelancaran administrasi.</li>
            </ul>
        </div>

        <div class="bg-white p-8 shadow-lg rounded-2xl">
            <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nomor Pekerja --}}
                <x-input name="nopekerja" label="Nomor Pekerja" value="{{ old('nopekerja', $pegawai->nopekerja) }}" required />

                {{-- Nama Pegawai --}}
                <x-input name="nama_pegawai" label="Nama Pegawai" value="{{ old('nama_pegawai', $pegawai->nama_pegawai) }}" required />

                {{-- Email --}}
                <x-input name="email" label="Email" type="email" value="{{ old('email', $pegawai->email) }}" required />

                {{-- Tombol --}}
                <div class="flex justify-end mt-8">
                    <a href="{{ route('pegawai.index') }}"
                        class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

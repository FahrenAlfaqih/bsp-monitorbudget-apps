<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Departemen
            </h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Panduan Pengisian --}}
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Pengeditan Departemen</h3>
            <ul class="list-disc list-inside text-sm space-y-1 ml-5">
                <li><strong>Nama Departemen:</strong> Ubah nama departemen sesuai kebutuhan.</li>
                <li><strong>Nomor BS:</strong> Pastikan nomor BS sesuai dengan struktur organisasi yang berlaku.</li>
                <li><strong>Email:</strong> Email ini adalah akun login departemen dan <em>tidak dapat diubah</em> di halaman ini.</li>
                <li class="text-blue-600"><strong>Catatan:</strong> Hanya departemen itu sendiri yang dapat mengubah password dan email.</li>
            </ul>
        </div>

        <div class="bg-white p-8 shadow-lg rounded-2xl">
            <form action="{{ route('departemen.update', $departemen->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama Departemen --}}
                <x-input name="nama" label="Nama Departemen" value="{{ old('nama', $departemen->nama) }}" required />

                {{-- Nomor BS --}}
                <x-input name="bs_number" label="Nomor BS" value="{{ old('bs_number', $departemen->bs_number) }}" required />

                {{-- Email --}}
                <x-input name="email" label="Email" value="{{ old('email', $departemen->email) }}" readonly />

                {{-- Tombol --}}
                <div class="flex justify-end mt-8">
                    <a href="{{ route('departemen.index') }}"
                        class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                        Update Departemen
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

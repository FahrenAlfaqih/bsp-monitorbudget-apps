<x-app-layout>


    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Panduan Pengisian --}}
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Penambahan Departemen</h3>
            <ul class="list-disc list-inside text-sm space-y-1 ml-5">
                <li><strong>Nama Departemen:</strong> Masukkan nama departemen dengan format yang jelas. Contoh: <em>Periode Anggaran 2025</em>.</li>
                <li><strong>Nomor BS:</strong> Isikan nomor pengelompokan departemen sesuai struktur organisasi. Contoh: <em>4</em>, <em>6</em>, <em>8</em>.</li>
                <li><strong>Email:</strong> Gunakan alamat email resmi departemen. Akun ini akan digunakan untuk login ke sistem, default (namadept@admindept.com).</li>
                <li><strong>Password:</strong> Buatlah password yang kuat minimal 8 karakter. Password ini akan digunakan oleh departemen untuk login.</li>
                <li><strong>Konfirmasi Password:</strong> Masukkan ulang password untuk verifikasi agar tidak terjadi kesalahan input.</li>
                <li class="text-blue-600"><strong>Catatan:</strong> Sistem akan otomatis membuat akun pengguna menggunakan data email dan password yang diisi di form ini. Akun tersebut akan memiliki peran sebagai <em>departemen</em> dan hanya bisa mengakses data departemennya sendiri.</li>
            </ul>
        </div>

        <div class="bg-white p-8 shadow-lg rounded-2xl">
            <form action="{{ route('departemen.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Nama Departemen --}}
                <x-input name="nama" label="Nama Departemen" required />

                {{-- Nomor BS --}}
                <x-input name="bs_number" label="Nomor BS" required />

                {{-- Email --}}
                <x-input name="email" label="Email" type="email" required />

                {{-- Password --}}
                <x-input name="password" label="Password" type="password" required />

                {{-- Konfirmasi Password --}}
                <x-input name="password_confirmation" label="Konfirmasi Password" type="password" required />

                {{-- Tombol --}}
                <div class="flex justify-end mt-8">
                    <a href="{{ route('departemen.index') }}"
                        class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                        Simpan Departemen
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
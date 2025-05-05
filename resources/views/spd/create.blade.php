<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Surat Perjalanan Dinas') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <form action="{{ route('spd.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Departemen --}}
                        <div>
                            <label for="departemen_id" class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                            <select name="departemen_id" id="departemen_id" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                <option value="">Pilih Departemen</option>
                                @foreach($departemen as $dept)
                                <!-- <option value="{{ $dept->id }}">{{ $dept->name }}</option> -->
                                <option value="{{ $dept->id }}" {{ request('departemen') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- {{-- Nama Pegawai --}}
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Pegawai</label>
                            <select name="user_id" id="user_id" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Pegawai</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div> -->

                        {{-- Nomor SPD --}}
                        <div>
                            <label for="nomor_spd" class="block text-sm font-medium text-gray-700 mb-1">Nomor SPD</label>
                            <input type="text" name="nomor_spd" id="nomor_spd" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Nama Pegawai --}}
                        <div>
                            <label for="pegawai_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Pegawai</label>
                            <select name="pegawai_id" id="pegawai_id" class="w-full select2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($pegawais as $pegawai)
                                <option value="{{ $pegawai->id }}">{{ $pegawai->nama_pegawai }} </option>
                                @endforeach
                            </select>
                        </div>


                        {{-- Asal --}}
                        <div>
                            <label for="asal" class="block text-sm font-medium text-gray-700 mb-1">Asal</label>
                            <input type="text" name="asal" id="asal" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Tujuan --}}
                        <div>
                            <label for="tujuan" class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                            <input type="text" name="tujuan" id="tujuan" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Tanggal Berangkat --}}
                        <div>
                            <label for="tanggal_berangkat" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berangkat</label>
                            <input type="date" name="tanggal_berangkat" id="tanggal_berangkat" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Tanggal Kembali --}}
                        <div>
                            <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Jenis Transport --}}
                        <div>
                            <label for="jenis_transport" class="block text-sm font-medium text-gray-700 mb-1">Jenis Transport</label>
                            <select name="jenis_transport" id="jenis_transport" required
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                <option value="">Pilih Jenis Transport</option>
                                <option value="Udara">Udara</option>
                                <option value="Darat">Darat</option>
                            </select>
                        </div>


                        {{-- Nama Transport --}}
                        <div>
                            <label for="nama_transport" class="block text-sm font-medium text-gray-700 mb-1">Nama Transport</label>
                            <input type="text" name="nama_transport" id="nama_transport"
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Kegiatan --}}
                        <div class="md:col-span-2">
                            <label for="kegiatan" class="block text-sm font-medium text-gray-700 mb-1">Kegiatan</label>
                            <textarea name="kegiatan" id="kegiatan" rows="4"
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end mt-8">
                        <a href="{{ route('spd.index') }}"
                            class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                            Simpan SPD
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Cari pegawai...",
                allowClear: true
            });
        });
    </script>
    @endpush

</x-app-layout>
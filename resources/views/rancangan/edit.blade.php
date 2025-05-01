<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Rancangan Anggaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-xl p-8">
                <form action="{{ route('rancangan.update', $rancangan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Periode --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="periode_id">Periode</label>
                        <select name="periode_id" id="periode_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            @foreach ($periode as $p)
                            <option value="{{ $p->id }}" {{ $rancangan->periode_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_periode }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah Anggaran --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="jumlah_anggaran">Jumlah Anggaran</label>
                        <input type="number" name="jumlah_anggaran" id="jumlah_anggaran"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500"
                            value="{{ old('jumlah_anggaran', $rancangan->jumlah_anggaran) }}" min="0" required>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="status">Status</label>
                        <select name="status" id="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            <option value="menunggu" {{ $rancangan->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ $rancangan->status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ $rancangan->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="catatan">Catatan (Opsional)</label>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">{{ old('catatan', $rancangan->catatan) }}</textarea>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('rancangan.index') }}"
                            class="inline-block px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Batal</a>
                        <button type="submit"
                            class="inline-block px-6 py-2 bg-purple-700 text-white rounded-lg hover:bg-purple-800 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
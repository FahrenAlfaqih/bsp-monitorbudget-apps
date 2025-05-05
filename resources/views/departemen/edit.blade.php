<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data Departemen
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('departemen.update', $departemen->id) }}" method="POST" class="bg-white p-6 rounded shadow-sm">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium">Nama Departemen </label>
                <input type="text" name="nama" value="{{ old('nama', $departemen->nama) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Nomor BS</label>
                <input type="text" name="bs_number" value="{{ old('bs_number', $departemen->bs_number) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="text" readonly="readonly" name="email" value="{{ old('email', $departemen->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="flex justify-end mt-8">
                <a href="{{ route('departemen.index') }}" class="inline-block px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg mr-3 transition">
                    Batal
                </a>
                <button type="submit" class="inline-block px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700 font-medium text-sm rounded-lg shadow-md transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
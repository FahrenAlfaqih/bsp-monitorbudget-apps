<x-app-layout>


    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Panduan Pengelolaan Rancangan Anggaran -->
        <div class="bg-gray-200 text-gray-800 p-4 mb-6 rounded-lg border border-gray-300">
            <h3 class="font-semibold mb-2">Panduan Pengelolaan Rancangan Anggaran</h3>

            @if(Auth::user()->role === 'admindept' || Auth::user()->role === 'admindept_hcm')
            <ul class="list-disc list-inside text-sm space-y-1">
                <li>Setelah diajukan, status berubah menjadi <strong>Menunggu</strong>.</li>
                <li>Anggaran akan ditinjau oleh Tim HCM dan Finance untuk disetujui atau ditolak.</li>
                <li>Jika <strong>disetujui</strong>, anggaran akan terkunci dan digunakan untuk program kerja selama 1 tahun.</li>
                <li>Jika <strong>ditolak</strong>, catatan evaluasi akan diberikan dan anggaran bisa diajukan kembali</li>
            </ul>

            @elseif(Auth::user()->role === 'tmhcm')
            <ul class="list-disc list-inside text-sm space-y-1">
                <li><strong>Peninjauan:</strong> Tinjau setiap pengajuan anggaran dari departemen yang statusnya <strong>Menunggu</strong>.</li>
                <li><strong>Evaluasi:</strong> Pastikan anggaran sesuai dengan kebutuhan dan standar biaya perjalanan dinas.</li>
                <li><strong>Keputusan:</strong> Pilih <strong>Setujui</strong> jika anggaran layak, atau <strong>Tolak</strong> jika perlu revisi.</li>
                <li><strong>Finalisasi:</strong> Jika disetujui, sistem akan mengunci nilai anggaran untuk digunakan 1 tahun penuh.</li>
            </ul>

            @endif
        </div>

        <!-- Filter Periode -->
        <div class="bg-white p-6 shadow-md rounded-lg mb-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4">Filter Rancangan Anggaran</h3>

            <form id="filterForm" class="flex flex-wrap gap-3 sm:gap-4 items-end">
                @if (Auth::user()->role === 'tmhcm')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                    <select name="departemen" id="departemen"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Departemen --</option>
                        @foreach ($departemenList as $dep)
                        <option value="{{ $dep->id }}" {{ request('departemen') == $dep->id ? 'selected' : '' }}>
                            {{ $dep->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Status --</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <select name="periode" id="periode"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Periode --</option>
                        @foreach ($periodeList as $periode)
                        <option value="{{ $periode->id }}" {{ request('periode') == $periode->id ? 'selected' : '' }}>
                            {{ $periode->nama_periode }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit"
                        class="mt-5 text-sm px-4 py-2 border border-blue-500 text-blue-600 rounded-lg shadow-sm transition hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 shadow-md rounded-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                <h3 class="font-semibold text-lg text-gray-800">Rancangan Anggaran Departemen</h3>
            </div>

            @if($rancangan->isEmpty())
            <p class="mt-2 text-gray-500">Data pengajuan anggaran tidak tersedia.</p>
            @else
            @include('rancangan._filterResult')
            @endif
        </div>

    </div>
</x-app-layout>
<script>
    $(document).ready(function() {
        $('#filterForm').submit(function(e) {
            e.preventDefault();

            var data = $(this).serialize(); 

            $.ajax({
                url: "{{ route('rancangan.index') }}", 
                type: "GET",
                data: data, 
                success: function(response) {
                    $('table tbody').html($(response).find('table tbody').html());
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
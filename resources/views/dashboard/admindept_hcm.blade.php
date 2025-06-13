<x-app-layout>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <p id="welcomeText"></p>
        <div class="mt-3">

            <div class="mb-6">
                <form method="GET" action="{{ route('dashboard.admindept_hcm') }}"
                class="flex flex-wrap gap-3 sm:gap-4 items-end mb-4">
                <select name="periode_id" id="periode_id"
                    class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Periode --</option>
                    @foreach ($semuaPeriode as $periode)
                    <option value="{{ $periode->id }}"
                        {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                        {{ $periode->nama_periode }}
                    </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="mt-5 text-sm px-4 py-2 border border-blue-500 text-blue-600 rounded-lg shadow-sm transition hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <i class="fas fa-filter mr-1"></i> Tampilkan
                </button>
                </form>
                <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                    <h3 class="text-md font-semibold mb-4">Jumlah Laporan SPD Seluruh Departemen Per Bulan</h3>
                    <canvas id="jumlahSpdChart" height="200"></canvas>
                </div>
            </div>

            <h3 class="text-lg font-semibold mt-4">Rekap Status Pelaporan SPD Seluruh Departemen </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">Total SPD</h4>
                    <p class="text-2xl font-semibold">{{ $totalSpd }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">Total DPD</h4>
                    <p class="text-2xl font-semibold">{{ $totalDpd }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">SPD yang Ditolak</h4>
                    <p class="text-2xl font-semibold text-red-600">{{ $spdDitolak }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">SPD yang Disetujui</h4>
                    <p class="text-2xl font-semibold text-green-600">{{ $spdDisetujui }}</p>
                </div>
                <div class="bg-white rounded-xl shadow p-4 h-full flex flex-col justify-between">
                    <h4 class="text-lg font-bold mb-2">SPD yang Diajukan</h4>
                    <p class="text-2xl font-semibold text-blue-600">{{ $spdDiajukan }}</p>
                </div>
            </div>

            {{-- Row 1: Bar Chart Total Biaya Per Bulan | Pie Chart SPD Per Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 mt-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <h4 class="text-md font-semibold mb-4">Total Biaya Perjalanan Dinas Departemen HCM Per Bulan</h4>
                    <canvas id="biayaPerBulanChart" height="250"></canvas>
                </div>
                <div class="bg-white rounded-xl shadow p-6">
                    <h4 class="text-md font-semibold mb-4">Rata-rata Biaya Perjalanan Dinas Departemen HCM Per Pegawai</h4>
                    <canvas id="rataRataBiayaChart" height="250"></canvas>
                </div>
            </div>

            {{-- Row 2: Horizontal Bar Chart Jumlah SPD Per Pegawai | Bar Chart Rata-rata Biaya Per Pegawai --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <h4 class="text-md font-semibold mb-4">Jumlah SPD Departemen HCM Per Pegawai</h4>
                    <canvas id="jumlahSpdPerPegawaiChart" height="250"></canvas>
                </div>
<div class="bg-white rounded-xl shadow p-6">
    <h4 class="text-md font-semibold mb-4 text-left">Progress Penggunaan Anggaran Dinas Departemen HCM</h4>
    <div style="width: 350px; height: 350px; margin: 0 auto;">
        <canvas id="pieAnggaran"></canvas>
        <div class="mt-2 text-gray-600 text-sm text-center">
            Terpakai: <b>Rp {{ number_format($usedBudget,0,',','.') }}</b> <br>
            Sisa: <b>Rp {{ number_format($remainingBudget,0,',','.') }}</b>
        </div>
    </div>
</div>

            </div>


            
            <h3 class="text-lg font-semibold mt-4">Top List Anggaran Departemen </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                {{-- Top Karyawan --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-semibold text-black-700 mb-4">Top Karyawan dengan Biaya Dinas Tertinggi
                    </h4>
                    <div class="text-sm text-gray-700 space-y-2">
                        @forelse($topKaryawan as $karyawan)
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $loop->iteration }}.
                                {{ $karyawan->nama_pegawai }}</span>
                            <span class="text-gray-800 font-semibold">Rp
                                {{ number_format($karyawan->total_biaya, 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <p class="text-gray-500 italic">Data tidak tersedia.</p>
                        @endforelse
                    </div>

                </div>


                {{-- Top Budget Departemen --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-semibold text-black-700 mb-4">Top Budget Departemen</h4>
                    <ul class="text-sm text-gray-700 space-y-2">
                        @forelse($topBudget as $budget)
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $loop->iteration }}.
                                {{ $budget->nama_pegawai }}</span>
                            <span class="text-gray-800 font-semibold">Rp
                                {{ number_format($budget->total_biaya, 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <p class="text-gray-500 italic">Data tidak tersedia.</p>
                        @endforelse
                    </ul>
                </div>

                {{-- Tujuan Dinas Paling Sering --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-semibold text-black-700 mb-4">Tujuan Dinas Paling Sering</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @forelse($topTujuanDinas as $tujuan)
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $loop->iteration }}. {{ $tujuan->tujuan }}</span>
                            <span class="text-gray-800 font-semibold">{{ $tujuan->jumlah_tujuan }} kali</span>
                        </div>
                        @empty
                        <p class="text-gray-500 italic">Data tidak tersedia.</p>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="text-lg font-semibold mb-4">Periode Anggaran Aktif</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($periodeAktif as $item)
                    <div class="bg-white rounded-xl shadow p-6 h-full flex flex-col justify-between">
                        <div>
                            <h4 class="text-lg font-bold mb-2">{{ $item->nama_periode ?? '-' }}</h4>
                            <p class="text-sm text-gray-600">
                                Mulai:
                                {{ $item->mulai ? \Carbon\Carbon::parse($item->mulai)->format('d M Y') : '-' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Berakhir:
                                {{ $item->berakhir ? \Carbon\Carbon::parse($item->berakhir)->format('d M Y') : '-' }}
                            </p>

                            @if ($periodeTerpilih && ($periodeTerpilih->sudahMengajukan ?? false))
                            <div class="mt-2 text-sm">
    @if ($periodeTerpilih->statusPengajuan === 'menunggu')
        <p class="mt-2 text-sm text-gray-600">Status Pengajuan:
            <span class="inline-block px-2 py-1 rounded-full text-white bg-yellow-500">
                Menunggu Persetujuan
            </span>
        </p>
    @elseif ($periodeTerpilih->statusPengajuan === 'disetujui')
        <p class="mt-2 text-sm text-gray-600">Status Pengajuan:
            <span class="inline-block px-2 py-1 rounded-full text-white bg-green-500">
                Disetujui
            </span>
        </p>
    @elseif ($periodeTerpilih->statusPengajuan === 'ditolak')
        <p class="mt-2 text-sm text-gray-600">Status Pengajuan:
            <span class="inline-block px-2 py-1 rounded-full text-white bg-red-500">
                Ditolak
            </span>
        </p>
    @endif
                            </div>
                            @endif

                            <p class="mt-2 text-sm">
                                Status:
                                <span
                                    class="inline-block px-2 py-1 rounded-full text-white
                            {{ ($item->status ?? '') === 'dibuka' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($item->status ?? 'Tidak diketahui') }}
                                </span>
                            </p>
                        </div>

                        <div class="mt-4">
                            @if ($item->sudahMengajukan ?? false)
                            <a href="{{ route('rancangan.index') }}"
                                class="inline-block w-full px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                                Lihat Pengajuan Anggaran
                            </a>
                            @else
                            <a href="{{ route('rancangan.create', ['periode_id' => $item->id ?? 0]) }}"
                                class="inline-block w-full px-6 py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                                Ajukan Anggaran
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    @if ($periodeAktif->isEmpty())
                    <div class="bg-white rounded-xl shadow p-6 text-center col-span-full">
                        <p class="text-gray-500 italic">Tidak ada periode anggaran yang aktif untuk pengajuan.
                        </p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

      let labels = @json($labels);  // Labels untuk bulan (format YYYY-MM)
let data = @json($data);  // Jumlah SPD per bulan

new Chart(document.getElementById('jumlahSpdChart'), {
    type: 'line',
    data: {
        labels: labels, // Labels untuk bulan
        datasets: [{
            label: 'Jumlah SPD',
            data: data,  // Jumlah SPD per bulan
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.3)',
            fill: true,
            tension: 0.3,
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,  // Increment angka pada sumbu Y setiap 1
                }
            }
        }
    }
});

        // Data 1: Bar Chart Total Biaya Per Bulan
        const biayaPerBulanLabels = @json($biayaPerBulan -> pluck('bulan'));
        const biayaPerBulanData = @json($biayaPerBulan -> pluck('total_biaya'));

        new Chart(document.getElementById('biayaPerBulanChart'), {
            type: 'bar',
            data: {
                labels: biayaPerBulanLabels,
                datasets: [{
                    label: 'Total Biaya Perjalanan (Rp)',
                    data: biayaPerBulanData,
                    backgroundColor: '#2563eb',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: val => 'Rp ' + val.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });

        // Data 2 : Pie chart Penggunaan Anggaran
        new Chart(document.getElementById('pieAnggaran').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Terpakai', 'Sisa'],
        datasets: [{
            data: [{{ $usedBudget }}, {{ $remainingBudget }}],
            backgroundColor: ['#22c55e', '#eab308'],
        }]
    },
    options: {
        maintainAspectRatio: true, 
        aspectRatio: 1,             
        layout: {
            padding: 10
        },
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

        // Data 3: Horizontal Bar Chart Jumlah SPD Per Pegawai
        const spdPegawaiLabels = @json($jumlahSpdPerPegawai -> pluck('nama_pegawai'));
        const spdPegawaiData = @json($jumlahSpdPerPegawai -> pluck('jumlah_spd'));

        new Chart(document.getElementById('jumlahSpdPerPegawaiChart'), {
            type: 'bar',
            data: {
                labels: spdPegawaiLabels,
                datasets: [{
                    label: 'Jumlah SPD',
                    data: spdPegawaiData,
                    backgroundColor: '#2563eb',
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return Math.round(value);
                            },
                        stepSize: 1, 
                        }
                    }
                }
            }
        });

        // Data 4: Bar Chart Rata-rata Biaya Per Pegawai
        const rataRataBiayaLabels = @json($rataRataBiayaPerPegawai -> pluck('nama_pegawai'));
        const rataRataBiayaData = @json($rataRataBiayaPerPegawai -> pluck('rata_rata_biaya'));

        new Chart(document.getElementById('rataRataBiayaChart'), {
            type: 'bar',
            data: {
                labels: rataRataBiayaLabels,
                datasets: [{
                    label: 'Rata-rata Biaya (Rp)',
                    data: rataRataBiayaData,
                    backgroundColor: '#f59e0b',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: val => 'Rp ' + val.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });


    </script>


</x-app-layout>
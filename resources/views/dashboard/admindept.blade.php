<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p class="mb-4">Selamat datang, Admin  {{ auth()->user()->name }}!</p>
            <form method="GET" action="{{ route('dashboard.admindept') }}" 
            class="flex flex-wrap gap-3 sm:gap-4 items-end mb-4 mt-3">
                <select name="periode_id" id="periode_id"
                    class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Periode --</option>
                    @foreach($semuaPeriode as $periode)
                    <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                        {{ $periode->nama_periode }}
                    </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="px-4 py-2 text-sm border border-blue-500 text-blue-600 rounded-lg shadow-sm transition hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <i class="fas fa-filter mr-1"></i> Tampilkan
                </button>
            </form>


<div class="max-w-7xl mx-auto">
    {{-- LINE CHART: TREND DINAS & BIAYA --}}
    <div class="bg-white shadow-lg rounded-xl mb-8 p-6">
        <h4 class="font-semibold mb-4">Trend Perjalanan Dinas & Biaya Bulanan</h4>
        <canvas id="trendDinasChart" height="80"></canvas>
    </div>

                {{-- Row 1: Bar Chart Total Biaya Per Bulan | Pie Chart SPD Per Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <h4 class="text-md font-semibold mb-4">Total Biaya Perjalanan Dinas {{ auth()->user()->name }} Per Bulan</h4>
                    <canvas id="biayaPerBulanChart" height="250"></canvas>
                </div>
                <div class="bg-white rounded-xl shadow p-6">
                    <h4 class="text-md font-semibold mb-4">Rata-rata Biaya Perjalanan Dinas {{ auth()->user()->name }} Per Pegawai</h4>
                    <canvas id="rataRataBiayaChart" height="250"></canvas>
                </div>
            </div>

            {{-- Row 2: Horizontal Bar Chart Jumlah SPD Per Pegawai | Bar Chart Rata-rata Biaya Per Pegawai --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <h4 class="text-md font-semibold mb-4">Jumlah SPD {{ auth()->user()->name }} Per Pegawai</h4>
                    <canvas id="jumlahSpdPerPegawaiChart" height="250"></canvas>
                </div>
<div class="bg-white rounded-xl shadow p-6">
    <h4 class="text-md font-semibold mb-4 text-left">Progress Penggunaan Anggaran Dinas {{ auth()->user()->name }}</h4>
    <div style="width: 350px; height: 350px; margin: 0 auto;">
        <canvas id="pieAnggaran"></canvas>
        <div class="mt-2 text-gray-600 text-sm text-center">
            Terpakai: <b>Rp {{ number_format($usedBudget,0,',','.') }}</b> <br>
            Sisa: <b>Rp {{ number_format($remainingBudget,0,',','.') }}</b>
        </div>
    </div>
</div>

            </div>

</div>
            <h3 class="text-lg font-semibold mt-4">Top List Anggaran Departemen </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6 mb-6">
                {{-- Top Karyawan --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-semibold text-black-700 mb-4">Top Karyawan dengan Biaya Dinas Tertinggi</h4>
                    <div class="text-sm text-gray-700 space-y-2">
                        @foreach($topKaryawan as $karyawan)
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $loop->iteration }}. {{ $karyawan->nama_pegawai }}</span>
                            <span class="text-gray-800 font-semibold">Rp {{ number_format($karyawan->total_biaya, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>


                {{-- Top Budget Departemen --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-semibold text-black-700 mb-4">Top Budget Departemen</h4>
                    <ul class="text-sm text-gray-700 space-y-2">
                        @foreach($topBudget as $budget)
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $loop->iteration }}. {{ $budget->nama_pegawai }}</span>
                            <span class="text-gray-800 font-semibold">Rp {{ number_format($budget->total_biaya, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </ul>
                </div>

                {{-- Tujuan Dinas Paling Sering --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h4 class="text-lg font-semibold text-black-700 mb-4">Tujuan Dinas Paling Sering</h4>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach($topTujuanDinas as $tujuan)
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $loop->iteration }}. {{ $tujuan->tujuan }}</span>
                            <span class="text-gray-800 font-semibold">{{ $tujuan->jumlah_tujuan }} kali</span>
                        </div>
                        @endforeach
                    </ul>
                </div>
            </div>

            <h3 class="text-lg font-semibold mb-4">Periode Anggaran Aktif</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-bold mb-2">{{ $periodeTerpilih->nama_periode }}</h4>
                        <p class="text-sm text-gray-600">Mulai: {{ \Carbon\Carbon::parse($periodeTerpilih->mulai)->format('d M Y') }}</p>
                        <p class="text-sm text-gray-600">Berakhir: {{ \Carbon\Carbon::parse($periodeTerpilih->berakhir)->format('d M Y') }}</p>
                        @if ($periodeTerpilih->sudahMengajukan)
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
                            <span class="inline-block px-2 py-1 rounded-full text-white 
                {{ $periodeTerpilih->status === 'dibuka' ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ ucfirst($periodeTerpilih->status) }}
                            </span>
                        </p>


                    </div>

                    <div class="mt-4">
                        @if ($periodeTerpilih->status === 'ditutup')
                        <a href="{{ route('rancangan.index') }}"
                            class="inline-block w-full px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                            Lihat Pengajuan Anggaran
                        </a>
                        @else
                        @if ($periodeTerpilih->sudahMengajukan)
                        <a href="{{ route('rancangan.index') }}"
                            class="inline-block w-full px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                            Lihat Pengajuan Anggaran
                        </a>
                        @else
                        <a href="{{ route('rancangan.create', ['periode_id' => $periodeTerpilih->id]) }}"
                            class="inline-block w-full px-6 py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                            Ajukan Anggaran
                        </a>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        var typewriter = new Typewriter('#welcomeText', {
            loop: true,
            delay: 75,
        });

        typewriter
            .typeString('Selamat datang, Admin Departemen {{ auth()->user()->name }}!')
            .pauseFor(5000)
            .deleteAll()
            .start();
    </script>

    <script>
        const data = @json($data);
        console.log(data);
    </script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- Data Line Chart --- 
    const trendLabels = @json($trendDinas->pluck('tanggal'));
    const jumlahDinas = @json($trendDinas->pluck('jumlah_dinas'));
    const totalBiaya = @json($trendDinas->pluck('total_biaya'));

    new Chart(document.getElementById('trendDinasChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [
                {
                    label: 'Jumlah Dinas',
                    data: jumlahDinas,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.08)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Biaya Perjalanan Dinas',
                    data: totalBiaya,
                    borderColor: '#f59e42',
                    backgroundColor: 'rgba(245,158,66,0.08)',
                    fill: true,
                    tension: 0.3,
                    yAxisID: 'y2'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            stacked: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: { display: true, text: 'Jumlah Dinas' },
                    ticks: {
                        beginAtZero: true,   // Mulai dari angka 0
                        stepSize: 1,         // Increment angka di sumbu Y setiap 1
                        callback: function(value) {
                            return Math.round(value); // Membulatkan angka di sumbu Y
                        }
                    }
                },
                y2: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: { display: true, text: 'Total Biaya (Rp)' },
                    grid: { drawOnChartArea: false },
                    ticks: {
                        beginAtZero: true,   // Mulai dari angka 0
                        stepSize: 1000000,   // Increment per juta untuk Total Biaya
                        callback: function(value) {
                            return Math.round(value); // Membulatkan angka di sumbu Y2
                        }
                    }
                }
            }
        }
    });

    // --- Data Pie Chart --- 
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
            layout: { padding: 10 },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    const biayaPerBulanLabels = @json($biayaPerBulan->pluck('bulan'));
    const biayaPerBulanData = @json($biayaPerBulan->pluck('total_biaya'));

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

    // Data 3: Horizontal Bar Chart Jumlah SPD Per Pegawai
    const spdPegawaiLabels = @json($jumlahSpdPerPegawai->pluck('nama_pegawai'));
    const spdPegawaiData = @json($jumlahSpdPerPegawai->pluck('jumlah_spd'));

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
    const rataRataBiayaLabels = @json($rataRataBiayaPerPegawai->pluck('nama_pegawai'));
    const rataRataBiayaData = @json($rataRataBiayaPerPegawai->pluck('rata_rata_biaya'));

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
<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p  class="mb-4">Selamat datang, {{ auth()->user()->name }}</p>

            <form method="GET" action="{{ route('dashboard.tmhcm') }}" class="flex flex-wrap gap-3 sm:gap-4 items-end">
                <div>
                    <select name="periode_id" id="periode_id"
                        class="text-sm px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 transition hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Periode --</option>
@foreach($periodesDropdown as $periode)
                        <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                            {{ $periode->nama_periode }}
                        </option>
                        @endforeach
                    </select>

                    <button type="submit"
                        class="mt-5 ml-3 text-sm px-4 py-2 border border-blue-500 text-blue-600 rounded-lg shadow-sm transition hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <i class="fas fa-filter mr-1"></i> Tampilkan
                    </button>
                </div>
            </form>

            {{-- 1. LINE CHART: Trend Anggaran Periode --}}
            <div class="bg-white rounded-lg shadow p-4 mb-8 mt-6">
                <h3 class="font-semibold mb-4">Trend Anggaran Perjalanan Dinas per Periode</h3>
                <canvas id="trendAnggaranChart" height="100"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow p-4 mb-8 mt-6">
                <h3 class="font-semibold mb-4">Perbandingan Anggaran Dinas per Departemen per Periode</h3>
                <canvas id="anggaranGroupedBar" height="100"></canvas>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- 2. PIE CHART: Progress Anggaran per Departemen --}}
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="font-semibold mb-4">Progress Penggunaan Anggaran Dinas per Departemen</h3>
                    <canvas id="progressAnggaranPie" height="300"></canvas>
                </div>

                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- 3. BAR CHART: Penggunaan Anggaran Departemen Terbesar --}}
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="font-semibold mb-4"> Departemen dengan Penggunaan Anggaran Dinas Tertinggi</h3>
                        <canvas id="topDepartemenBar" height="300"></canvas>
                    </div>

                    {{-- 4. BAR CHART: Karyawan dengan Biaya Terbesar --}}
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="font-semibold mb-4"> Karyawan dengan Biaya Perjalanan Dinas Tertinggi</h3>
                        <canvas id="topKaryawanBar" height="300"></canvas>
                    </div>
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

                <div class="bg-white shadow-xl rounded-2xl p-6">
                    <h2 class="text-xl font-bold mb-4 text-blue-600">Top 10 Pegawai</h2>
                    <ul class="space-y-2">
                        @foreach($topKaryawan as $karyawan)
                        <li class="flex items-center justify-between border-b pb-2 text-sm">
                            <span class="font-semibold w-1/3">{{ $karyawan->nama_pegawai }}</span>
                            <span class="text-gray-500 w-1/3">{{ $karyawan->nama_departemen }}</span>
                            <span class="text-gray-700 font-medium w-1/3 text-right">Rp {{ number_format($karyawan->total_biaya, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Card Departemen -->
                <div class="bg-white shadow-xl rounded-2xl p-6">
                    <h2 class="text-xl font-bold mb-4 text-green-600">Top 10 Departemen </h2>
                    <ul class="space-y-2">
                        @foreach($topDepartemen as $dept)
                        <li class="flex items-center justify-between border-b pb-2 text-sm">
                            <span class="w-2/3 font-semibold">{{ $dept->nama }}</span>
                            <span class="w-1/3 text-right text-gray-700 font-medium">
                                Rp {{ number_format($dept->total_biaya, 0, ',', '.') }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Card Transportasi -->
                <div class="bg-white shadow-xl rounded-2xl p-6">
                    <h2 class="text-xl font-bold mb-4 text-purple-600">Top 10 Transportasi </h2>
                    <ul class="space-y-2 text-sm">
                        @foreach($topTransportasi as $trans)
                        <li class="flex justify-between border-b pb-2 items-center">
                            <div class="w-2/3">
                                <div class="font-semibold">{{ $trans->jenis_transport }}</div>
                                <div class="text-gray-500 text-xs">{{ $trans->nama_transport }}</div>
                            </div>
                            <div class="w-1/3 text-right text-gray-700 font-medium">
                                Rp {{ number_format($trans->total_biaya, 0, ',', '.') }}
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4">Periode Anggaran Terbaru</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($periodesDropdown as $periode)
                    <div class="bg-white rounded-xl shadow p-6 h-full flex flex-col justify-between">
                        <div>
                            <h4 class="text-lg font-bold mb-2">{{ $periode->nama_periode }}</h4>
                            <p class="text-sm text-gray-600">Mulai: {{ \Carbon\Carbon::parse($periode->mulai)->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600">Berakhir: {{ \Carbon\Carbon::parse($periode->berakhir)->format('d M Y') }}</p>
                            <p class="mt-2 text-sm">
                                Status:
                                <span class="inline-block px-2 py-1 rounded-full text-white 
                        {{ $periode->status === 'dibuka' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($periode->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('periode.index') }}"
                                class="inline-block w-full px-6 py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out transform hover:scale-105 text-center">
                                Kelola Periode
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-xl shadow p-6 text-center col-span-full">
                        <p class="text-gray-500">Belum ada data periode anggaran.</p>
                    </div>
                    @endforelse
                </div>

            </div>

        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
 const periodes = @json($periodes);
    const datasets = @json($datasets);

    new Chart(document.getElementById('anggaranGroupedBar'), {
        type: 'bar',
        data: {
            labels: periodes,
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                x: { stacked: false },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + val.toLocaleString('id-ID')
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const val = ctx.parsed.y;
                            return ctx.dataset.label + ': Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

        var typewriter = new Typewriter('#welcomeText', {
            loop: true,
            delay: 75,
        });

        typewriter
            .typeString('Selamat datang, {{ auth()->user()->name }}!')
            .pauseFor(5000) // jeda 10 detik (dalam milidetik)
            .deleteAll()
            .start();
    </script>


    <script>
        // 1. Line Chart: Trend Anggaran
        const trendLabels = @json($trendAnggaran->pluck('nama_periode'));
        const trendData = @json($trendAnggaran->pluck('total_anggaran'));

        new Chart(document.getElementById('trendAnggaranChart'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Total Anggaran',
                    data: trendData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.2)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: val => 'Rp ' + val.toLocaleString('id-ID')
                        }
                    }
                },
                plugins: {
                    legend: { display: true }
                }
            }
        });

        // 2. Pie Chart: Progress Anggaran per Departemen
        // Buat label dan data total anggaran & realisasi masing-masing departemen
        const depLabelsFull = @json($progressAnggaran->pluck('nama_departemen'));
const depLabels = depLabelsFull.map(label => label.replace(/^Departemen\s+/i, ''));

const anggaranTotal = @json($progressAnggaran->pluck('total_anggaran'));
const realisasiTotal = @json($progressAnggaran->pluck('total_realisasi'));

const anggaranTotalNum = anggaranTotal.map(x => Number(x));
const realisasiTotalNum = realisasiTotal.map(x => Number(x));

const progressPercent = anggaranTotalNum.map((total, idx) => {
    const real = realisasiTotalNum[idx];
    if (total === 0) return 0;
    let persen = (real / total) * 100;
    return persen < 0.1 && persen > 0 ? 0.1 : Math.round(persen * 100) / 100;
});

new Chart(document.getElementById('progressAnggaranPie'), {
    type: 'doughnut',
    data: {
        labels: depLabels,
        datasets: [{
            label: 'Progress Penggunaan (%)',
            data: progressPercent,
            backgroundColor: [
                '#2563eb','#4ade80','#fbbf24','#f87171','#a78bfa','#f472b6',
                '#60a5fa','#fcd34d','#f97316','#c084fc'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'right',
                    labels: {
                    font: {
                        size: 11  // kecilin font legend
                    }
                }

             },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        const idx = ctx.dataIndex;
                        return `${depLabels[idx]}: ${progressPercent[idx]}% (Rp ${realisasiTotalNum[idx].toLocaleString()} / Rp ${anggaranTotalNum[idx].toLocaleString()})`;
                    }
                }
            }
        }
    }
});



        // 3. Bar Chart: Top Departemen
        const topDepLabels = @json($topDepartemen->pluck('nama'));
        const topDepData = @json($topDepartemen->pluck('total_biaya'));

        new Chart(document.getElementById('topDepartemenBar'), {
            type: 'bar',
            data: {
                labels: topDepLabels,
                datasets: [{
                    label: 'Total Biaya (Rp)',
                    data: topDepData,
                    backgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
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

        // 4. Bar Chart: Top Karyawan
        const topKarLabels = @json($topKaryawan->pluck('nama_pegawai'));
        const topKarData = @json($topKaryawan->pluck('total_biaya'));

        new Chart(document.getElementById('topKaryawanBar'), {
            type: 'bar',
            data: {
                labels: topKarLabels,
                datasets: [{
                    label: 'Total Biaya (Rp)',
                    data: topKarData,
                    backgroundColor: '#4ade80'
                }]
            },
            options: {
                responsive: true,
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
<?php

namespace App\Http\Controllers;

use App\Models\AnggaranFix;
use App\Models\Dpd;
use App\Models\PeriodeAnggaran;
use App\Models\RancanganAnggaran;
use App\Models\Spd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;


/**
 * @desc Controller ini berfungsi untuk mengirim data ke dashboard Admin Departemen HCM dan Selain Admin Departemen
 */
class AdminDeptController extends Controller
{
    /**
     * @desc fungsi ini untuk mengirim data terkait kebutuhan di dashboard Admin Departemen
     * @param Request request dari link url, yakni ada periode_id, digunakan ketika ingin memfilter periode
     * @return void akan mengembalikan data data variabel ke views dashboard/admindept
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $semuaPeriode = PeriodeAnggaran::orderBy('mulai', 'desc')->get();

        // Gunakan 'periode_id' dari query string, default ke periode pertama
        $periodeIdDipilih = $request->get('periode_id') ?? $semuaPeriode->first()?->id;

        $periodeTerpilih = PeriodeAnggaran::find($periodeIdDipilih);

        if (!$periodeTerpilih) {
            return redirect()->back()->with('error', 'Periode tidak ditemukan.');
        }

        // Total anggaran dan pengeluaran untuk periode terpilih
        $totalAnggaran = AnggaranFix::where('departemen_id', $user->departemen->id)
            ->where('periode_id', $periodeTerpilih->id)
            ->sum('jumlah_anggaran');

        $totalPengeluaran = DB::table('deklarasi_perjalanan_dinas')
            ->join('surat_perjalanan_dinas', 'deklarasi_perjalanan_dinas.spd_id', '=', 'surat_perjalanan_dinas.id')
            ->where('surat_perjalanan_dinas.departemen_id', $user->departemen->id)
            ->whereBetween('deklarasi_perjalanan_dinas.tanggal_deklarasi', [$periodeTerpilih->mulai, $periodeTerpilih->berakhir])
            ->sum('deklarasi_perjalanan_dinas.total_biaya');

        $periodeTerpilih->total_anggaran_disetujui = $totalAnggaran;
        $periodeTerpilih->total_pengeluaran = $totalPengeluaran;
        $periodeTerpilih->sisa_anggaran = $totalAnggaran - $totalPengeluaran;

        $rancangan = $user->role === 'tmhcm'
            ? RancanganAnggaran::with(['departemen', 'periode'])->get()
            : RancanganAnggaran::with('periode')
            ->where('departemen_id', $user->departemen->id)
            ->get();

        // Memperbarui data periode terpilih dengan status 'sudahMengajukan'
        $semuaPeriode = $semuaPeriode->map(function ($item) use ($user) {
            $item->sudahMengajukan = RancanganAnggaran::where('departemen_id', $user->departemen->id)
                ->where('periode_id', $item->id)
                ->exists();
            return $item;
        });

        $sudahMengajukan = RancanganAnggaran::where('departemen_id', $user->departemen->id)
            ->where('periode_id', $periodeTerpilih->id)
            // ->where('status', 'disetujui')
            ->exists();

        $pengajuan = RancanganAnggaran::where('departemen_id', $user->departemen->id)
            ->where('periode_id', $periodeTerpilih->id)
            ->latest()
            ->first();

        $periodeTerpilih->sudahMengajukan = $pengajuan ? true : false;
        $periodeTerpilih->statusPengajuan = $pengajuan->status ?? null;

        $mulaiPeriode = $periodeTerpilih->mulai ?? now()->startOfYear();
        $berakhirPeriode = $periodeTerpilih->berakhir ?? now()->endOfYear();
        // Data lainnya
        $topKaryawan = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->join('departemen', 's.departemen_id', '=', 'departemen.id')
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$periodeTerpilih->mulai, $periodeTerpilih->berakhir])
            ->select('s.nama_pegawai', DB::raw('SUM(d.total_biaya) as total_biaya'))
            ->groupBy('s.nama_pegawai')
            ->orderByDesc('total_biaya')
            ->limit(10)
            ->get();

        $topBudget = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->join('departemen', 's.departemen_id', '=', 'departemen.id')
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$periodeTerpilih->mulai, $periodeTerpilih->berakhir])
            ->select('s.nama_pegawai', DB::raw('SUM(d.total_biaya) as total_biaya'))
            ->groupBy('s.nama_pegawai')
            ->orderByDesc('total_biaya')
            ->limit(10)
            ->get();

        $topTujuanDinas = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->select('s.tujuan', DB::raw('COUNT(s.tujuan) as jumlah_tujuan'))
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$periodeTerpilih->mulai, $periodeTerpilih->berakhir])
            ->groupBy('s.tujuan')
            ->orderByDesc('jumlah_tujuan')
            ->limit(5)
            ->get();

        PeriodeAnggaran::autoUpdateStatus();

        $departemen_id = auth()->user()->departemen_id;

        $data = DB::table('v_surat_perjalanan')
            ->where('departemen_id', $departemen_id)
            ->get();

        $jumlahDitolak = DB::table('rancangan_anggaran')
            ->where('status', 'ditolak')
            ->where('departemen_id', $user->departemen->id)
            ->count();

        if ($jumlahDitolak > 0) {
            Alert::warning('Perhatian', 'Pengajuan rancangan anggaran anda ditolak. Silakan periksa kembali.');
        }

        //Line chart
        // Tentukan bulan aktif (bisa dari request atau periode)
        $startOfMonth = Carbon::parse($periodeTerpilih->mulai)->startOfMonth();
        $endOfMonth   = Carbon::parse($periodeTerpilih->mulai)->endOfMonth();

        $trendDinas = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$startOfMonth, $endOfMonth])
            ->selectRaw('DATE(d.tanggal_deklarasi) as tanggal, COUNT(d.id) as jumlah_dinas, SUM(d.total_biaya) as total_biaya')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        //pie chart progress anggaran
        $usedBudget = $totalPengeluaran;
        $remainingBudget = max($totalAnggaran - $totalPengeluaran, 0);


        $barDinasKaryawan = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$startOfMonth, $endOfMonth])
            ->select('s.nama_pegawai', DB::raw('COUNT(d.id) as total_dinas'))
            ->groupBy('s.nama_pegawai')
            ->orderByDesc('total_dinas')
            ->get();

        if ($user->email == 'finec@admindept.com') {
            $spdMenunggu = Spd::where('status', 'diajukan')->count();
            if ($spdMenunggu > 0) {
                Alert::warning('Perhatian', 'Ada ' . $spdMenunggu . ' Pelaporan SPD yang belum diproses. Silakan periksa kembali.');
            }
        }

        $biayaPerBulan = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$mulaiPeriode, $berakhirPeriode])
            ->select(
                DB::raw("DATE_FORMAT(d.tanggal_deklarasi, '%Y-%m') as bulan"),
                DB::raw('SUM(d.total_biaya) as total_biaya')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $spdStatusCount = DB::table('surat_perjalanan_dinas')
            ->where('departemen_id', $user->departemen->id)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $jumlahSpdPerPegawai = DB::table('surat_perjalanan_dinas as s')
            ->join('deklarasi_perjalanan_dinas as d', 's.id', '=', 'd.spd_id')
            ->where('departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$mulaiPeriode, $berakhirPeriode])
            ->select('nama_pegawai', DB::raw('COUNT(*) as jumlah_spd'))
            ->groupBy('nama_pegawai')
            ->orderByDesc('jumlah_spd')
            ->limit(10)
            ->get();

        $rataRataBiayaPerPegawai = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$mulaiPeriode, $berakhirPeriode])
            ->select('s.nama_pegawai', DB::raw('AVG(d.total_biaya) as rata_rata_biaya'))
            ->groupBy('s.nama_pegawai')
            ->orderByDesc('rata_rata_biaya')
            ->limit(10)
            ->get();

        return view('dashboard.admindept', [
            'rancangan' => $rancangan,
            'periodeTerpilih' => $periodeTerpilih,
            'semuaPeriode' => $semuaPeriode,
            'topKaryawan' => $topKaryawan,
            'topBudget' => $topBudget,
            'topTujuanDinas' => $topTujuanDinas,
            'data' => $data,
            'trendDinas' => $trendDinas,
            'usedBudget' => $usedBudget,
            'remainingBudget' => $remainingBudget,
            'barDinasKaryawan' => $barDinasKaryawan,
            'biayaPerBulan' => $biayaPerBulan,
            'spdStatusCount' => $spdStatusCount,
            'jumlahSpdPerPegawai' => $jumlahSpdPerPegawai,
            'rataRataBiayaPerPegawai' => $rataRataBiayaPerPegawai

        ]);
    }

    /**
     * @desc fungsi ini untuk mengirim data terkait kebutuhan di dashboard Admin Departemen HCM
     *
     * @return void akan mengembalikan data untuk kebutuhan chart ke views dashboard/admindept_hcm
     */
    public function dashboardAdminHCM(Request $request)
    {
        $user = Auth::user();
        if (!$user->departemen) {
            Alert::warning('Perhatian', 'User belum memiliki departemen terkait.');
            return view('dashboard.admindept_hcm', [
                'rancangan' => collect(),
                'semuaPeriode' => collect(),
                'periodeTerpilih' => null,
                'periodeAktif' => collect(),
                'totalSpd' => 0,
                'totalDpd' => 0,
                'spdDitolak' => 0,
                'spdDisetujui' => 0,
                'spdDiajukan' => 0,
                'topKaryawan' => collect(),
                'topBudget' => collect(),
                'topTujuanDinas' => collect(),
            ]);
        }

        if ($user->role === 'tmhcm') {
            $rancangan = RancanganAnggaran::with(['departemen', 'periode'])->get();
        } else {
            $rancangan = RancanganAnggaran::with('periode')
                ->where('departemen_id', $user->departemen->id)
                ->get();
        }

        $periodeAktif = PeriodeAnggaran::where('status', 'dibuka')->get();
        $periodeAktif = $periodeAktif->map(function ($item) use ($user) {
            $item->sudahMengajukan = RancanganAnggaran::where('departemen_id', $user->departemen->id)
                ->where('periode_id', $item->id)
                ->exists();
            return $item;
        });

        PeriodeAnggaran::autoUpdateStatus();
        $semuaPeriode = PeriodeAnggaran::orderBy('mulai', 'desc')->get();
        $semuaPeriode = $semuaPeriode->map(function ($item) use ($user) {
            $item->sudahMengajukan = RancanganAnggaran::where('departemen_id', $user->departemen->id)
                ->where('periode_id', $item->id)
                ->exists();
            return $item;
        });

        $periodeIdDipilih = $request->get('periode_id') ?? $semuaPeriode->first()?->id;
        $periodeTerpilih = $periodeIdDipilih ? PeriodeAnggaran::find($periodeIdDipilih) : null;

        if (!$periodeTerpilih) {
            $periodeTerpilih = null;
            $pengajuan = null;
        } else {
            $pengajuan = RancanganAnggaran::where('departemen_id', $user->departemen->id)
                ->where('periode_id', $periodeTerpilih->id)
                ->latest()
                ->first();
        }


        if ($periodeTerpilih) {
            $periodeTerpilih->sudahMengajukan = $pengajuan ? true : false;
            $periodeTerpilih->statusPengajuan = $pengajuan->status ?? null;
        }

        $mulaiPeriode = $periodeTerpilih->mulai ?? now()->startOfYear();
        $berakhirPeriode = $periodeTerpilih->berakhir ?? now()->endOfYear();

        $totalSpd = Spd::whereBetween('tanggal_berangkat', [$mulaiPeriode, $berakhirPeriode])->count();
        $totalDpd = Dpd::whereBetween('tanggal_deklarasi', [$mulaiPeriode, $berakhirPeriode])->count();
        $spdDitolak = Spd::where('status', 'ditolak')->whereBetween('tanggal_berangkat', [$mulaiPeriode, $berakhirPeriode])->count();
        $spdDisetujui = Spd::where('status', 'disetujui')->whereBetween('tanggal_berangkat', [$mulaiPeriode, $berakhirPeriode])->count();
        $spdDiajukan = Spd::where('status', 'diajukan')->whereBetween('tanggal_berangkat', [$mulaiPeriode, $berakhirPeriode])->count();

        if ($spdDitolak > 0) {
            Alert::warning('Perhatian', 'Ada ' . $spdDitolak . ' SPD yang ditolak. Silakan periksa kembali.');
        }

        $totalAnggaran = AnggaranFix::where('departemen_id', $user->departemen->id)
            ->where('periode_id', $periodeTerpilih->id)
            ->sum('jumlah_anggaran');

        $totalPengeluaran = DB::table('deklarasi_perjalanan_dinas')
            ->join('surat_perjalanan_dinas', 'deklarasi_perjalanan_dinas.spd_id', '=', 'surat_perjalanan_dinas.id')
            ->where('surat_perjalanan_dinas.departemen_id', $user->departemen->id)
            ->whereBetween('deklarasi_perjalanan_dinas.tanggal_deklarasi', [$periodeTerpilih->mulai, $periodeTerpilih->berakhir])
            ->sum('deklarasi_perjalanan_dinas.total_biaya');

        $periodeTerpilih->total_anggaran_disetujui = $totalAnggaran;
        $periodeTerpilih->total_pengeluaran = $totalPengeluaran;
        $periodeTerpilih->sisa_anggaran = $totalAnggaran - $totalPengeluaran;

        $usedBudget = $totalPengeluaran;
        $remainingBudget = max($totalAnggaran - $totalPengeluaran, 0);

        $topKaryawan = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->join('departemen', 's.departemen_id', '=', 'departemen.id')
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$mulaiPeriode, $berakhirPeriode])
            ->select('s.nama_pegawai', DB::raw('SUM(d.total_biaya) as total_biaya'))
            ->groupBy('s.nama_pegawai')
            ->orderByDesc('total_biaya')
            ->limit(10)
            ->get();

        $topBudget = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->join('departemen', 's.departemen_id', '=', 'departemen.id')
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$mulaiPeriode, $berakhirPeriode])
            ->select('s.nama_pegawai', DB::raw('SUM(d.total_biaya) as total_biaya'))
            ->groupBy('s.nama_pegawai')
            ->orderByDesc('total_biaya')
            ->limit(10)
            ->get();

        $topTujuanDinas = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->select('s.tujuan', DB::raw('COUNT(s.tujuan) as jumlah_tujuan'))
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$mulaiPeriode, $berakhirPeriode])
            ->groupBy('s.tujuan')
            ->orderByDesc('jumlah_tujuan')
            ->limit(5)
            ->get();

        $totalAnggaranDisetujui = 0;
        if ($periodeTerpilih && $user->departemen) {
            $totalAnggaranDisetujui = AnggaranFix::where('departemen_id', $user->departemen->id)
                ->where('periode_id', $periodeTerpilih->id)
                ->sum('jumlah_anggaran');
        }

        if ($periodeTerpilih) {
            $periodeTerpilih->totalAnggaranDisetujui = $totalAnggaranDisetujui;
        }


        $AnggaranTolak = DB::table('rancangan_anggaran')
            ->where('status', 'ditolak')
            ->where('departemen_id', $user->departemen->id)
            ->count();

        if ($AnggaranTolak > 0) {
            Alert::warning('Perhatian', 'Pengajuan rancangan anggaran anda ditolak. Silakan periksa kembali.');
        }

        $periodeIdFilter = $request->get('periode_id');
        $periodeTerpilih = PeriodeAnggaran::find($periodeIdFilter);
        $mulaiPeriode = $periodeTerpilih->mulai ?? now()->startOfYear();
        $berakhirPeriode = $periodeTerpilih->berakhir ?? now()->endOfYear();

        $queryPeriode = DB::table('surat_perjalanan_dinas as spd')
            ->join('periode_anggaran as p', 'spd.periode_id', '=', 'p.id')
            ->select('p.nama_periode', 'p.mulai', DB::raw('COUNT(spd.id) as jumlah_spd'))
            ->groupBy('p.nama_periode', 'p.mulai')
            ->orderBy('p.mulai');
        $jumlahSpdPerPeriode = $queryPeriode->get();

        $querySpdPerBulan = DB::table('surat_perjalanan_dinas as spd')
            ->select(DB::raw("DATE_FORMAT(spd.tanggal_berangkat, '%Y-%m') as bulan"), DB::raw('COUNT(spd.id) as jumlah_spd'))
            ->groupBy(DB::raw("DATE_FORMAT(spd.tanggal_berangkat, '%Y-%m')"))
            ->orderBy('bulan');


        if ($periodeIdFilter) {
            $querySpdPerBulan->whereBetween('spd.tanggal_berangkat', [$mulaiPeriode, $berakhirPeriode]);
        }

        $jumlahSpdPerBulan = $querySpdPerBulan->get();
        $labels = $jumlahSpdPerBulan->pluck('bulan');  // Label bulan (format YYYY-MM)
        $data = $jumlahSpdPerBulan->pluck('jumlah_spd');  // Jumlah SPD per bulan

        $biayaPerBulan = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$mulaiPeriode, $berakhirPeriode])
            ->select(
                DB::raw("DATE_FORMAT(d.tanggal_deklarasi, '%Y-%m') as bulan"),
                DB::raw('SUM(d.total_biaya) as total_biaya')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $spdStatusCount = DB::table('surat_perjalanan_dinas')
            ->where('departemen_id', $user->departemen->id)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $jumlahSpdPerPegawai = DB::table('surat_perjalanan_dinas as s')
            ->join('deklarasi_perjalanan_dinas as d', 's.id', '=', 'd.spd_id')
            ->where('departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$mulaiPeriode, $berakhirPeriode])
            ->select('nama_pegawai', DB::raw('COUNT(*) as jumlah_spd'))
            ->groupBy('nama_pegawai')
            ->orderByDesc('jumlah_spd')
            ->limit(10)
            ->get();

        $rataRataBiayaPerPegawai = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->where('s.departemen_id', $user->departemen->id)
            ->whereBetween('d.tanggal_deklarasi', [$mulaiPeriode, $berakhirPeriode])
            ->select('s.nama_pegawai', DB::raw('AVG(d.total_biaya) as rata_rata_biaya'))
            ->groupBy('s.nama_pegawai')
            ->orderByDesc('rata_rata_biaya')
            ->limit(10)
            ->get();

        return view(
            'dashboard.admindept_hcm',
            compact(
                'jumlahSpdPerPeriode',
                'jumlahSpdPerBulan',
                'semuaPeriode',         // Pastikan hanya satu kali
                'periodeTerpilih',      // Pastikan hanya satu kali
                'labels',
                'data',
                'totalSpd',
                'totalDpd',
                'spdDitolak',
                'spdDisetujui',
                'spdDiajukan',
                'spdStatusCount',
                'rancangan',
                'periodeAktif',
                'biayaPerBulan',
                'jumlahSpdPerPegawai',
                'rataRataBiayaPerPegawai',
                'usedBudget',
                'totalAnggaranDisetujui',
                'remainingBudget',
                'topKaryawan',
                'topBudget',
                'topTujuanDinas'
            )
        );
    }
}

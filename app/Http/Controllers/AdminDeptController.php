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
            ->where('status', 'disetujui')
            ->exists();

        $periodeTerpilih->sudahMengajukan = $sudahMengajukan;

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

        return view('dashboard.admindept', [
            'rancangan' => $rancangan,
            'periodeTerpilih' => $periodeTerpilih,
            'semuaPeriode' => $semuaPeriode,
            'topKaryawan' => $topKaryawan,
            'topBudget' => $topBudget,
            'topTujuanDinas' => $topTujuanDinas,
            'data' => $data
        ]);
    }

    /**
     * @desc fungsi ini untuk mengirim data terkait kebutuhan di dashboard Admin Departemen HCM
     *
     * @return void akan mengembalikan data untuk kebutuhan chart ke views dashboard/admindept_hcm
     */
    public function dashboardAdminHCM()
    {
        if (Auth::user()->role === 'tmhcm') {
            $rancangan = RancanganAnggaran::with(['departemen', 'periode'])->get();
        } else {
            $rancangan = RancanganAnggaran::with('periode')
                ->where('departemen_id', Auth::user()->departemen->id)
                ->get();
        }
        $periodeAktif = PeriodeAnggaran::where('status', 'dibuka')->get();
        $periodeAktif = $periodeAktif->map(function ($item) {
            $item->sudahMengajukan = RancanganAnggaran::where('departemen_id', Auth::user()->departemen->id)
                ->where('periode_id', $item->id)
                ->exists();
            return $item;
        });

        PeriodeAnggaran::autoUpdateStatus();

        $totalSpd = Spd::count();
        $totalDpd = Dpd::count();
        $spdDitolak = Spd::where('status', 'ditolak')->count();
        $spdDisetujui = Spd::where('status', 'disetujui')->count();
        $spdDiajukan = Spd::where('status', 'diajukan')->count();
        return view('dashboard.admindept_hcm', compact('rancangan', 'periodeAktif', 'totalSpd', 'totalDpd', 'spdDitolak', 'spdDisetujui', 'spdDiajukan'));
    }
}

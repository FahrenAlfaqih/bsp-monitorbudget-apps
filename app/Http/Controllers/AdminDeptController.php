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

class AdminDeptController extends Controller
{

    public function dashboard()
    {
        $user = Auth::user();

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

            $item->total_anggaran_disetujui = AnggaranFix::where('departemen_id', $user->departemen->id)
                ->where('periode_id', $item->id)
                ->sum('jumlah_anggaran');

            $item->total_pengeluaran = DB::table('deklarasi_perjalanan_dinas')
                ->join('surat_perjalanan_dinas', 'deklarasi_perjalanan_dinas.spd_id', '=', 'surat_perjalanan_dinas.id')
                ->where('surat_perjalanan_dinas.departemen_id', $user->departemen->id)
                ->whereBetween('deklarasi_perjalanan_dinas.tanggal_deklarasi', [$item->mulai, $item->berakhir])
                ->sum('deklarasi_perjalanan_dinas.total_biaya');


            $item->sisa_anggaran = $item->total_anggaran_disetujui - $item->total_pengeluaran;

            return $item;
        });

        PeriodeAnggaran::autoUpdateStatus();

        return view('dashboard.admindept', compact('rancangan', 'periodeAktif'));
    }

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

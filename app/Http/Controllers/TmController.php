<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodeAnggaran;
use Illuminate\Support\Facades\DB;

class TmController extends Controller
{
    public function dashboard()
    {

        $topKaryawan = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->join('departemen', 's.departemen_id', '=', 'departemen.id')
            ->select('s.nama_pegawai', 'departemen.nama as nama_departemen', DB::raw('SUM(d.total_biaya) as total_biaya'))
            ->groupBy('s.nama_pegawai', 'departemen.nama')
            ->orderByDesc('total_biaya')
            ->limit(10)
            ->get();


        $topDepartemen = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->join('departemen', 's.departemen_id', '=', 'departemen.id')
            ->select('departemen.nama', DB::raw('SUM(d.total_biaya) as total_biaya'))
            ->groupBy('departemen.nama')
            ->orderByDesc('total_biaya')
            ->limit(10)
            ->get();

        $topTransportasi = DB::table('surat_perjalanan_dinas as spd')
            ->join('deklarasi_perjalanan_dinas as dpd', 'spd.id', '=', 'dpd.spd_id')
            ->select('spd.jenis_transport', 'spd.nama_transport', DB::raw('SUM(dpd.total_biaya) as total_biaya'))
            ->groupBy('spd.jenis_transport', 'spd.nama_transport')
            ->orderByDesc('total_biaya')
            ->limit(10)
            ->get();


        $periodes = PeriodeAnggaran::orderBy('created_at', 'desc')->take(3)->get();
        return view('dashboard.tmhcm', compact('periodes', 'topKaryawan', 'topDepartemen', 'topTransportasi'));
    }
}

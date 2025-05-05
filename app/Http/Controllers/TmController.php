<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodeAnggaran;
use Illuminate\Support\Facades\DB;

class TmController extends Controller
{
    // public function dashboard()
    // {

    //     $topKaryawan = DB::table('deklarasi_perjalanan_dinas as d')
    //         ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
    //         ->join('departemen', 's.departemen_id', '=', 'departemen.id')
    //         ->select('s.nama_pegawai', 'departemen.nama as nama_departemen', DB::raw('SUM(d.total_biaya) as total_biaya'))
    //         ->groupBy('s.nama_pegawai', 'departemen.nama')
    //         ->orderByDesc('total_biaya')
    //         ->limit(10)
    //         ->get();


    //     $topDepartemen = DB::table('deklarasi_perjalanan_dinas as d')
    //         ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
    //         ->join('departemen', 's.departemen_id', '=', 'departemen.id')
    //         ->select('departemen.nama', DB::raw('SUM(d.total_biaya) as total_biaya'))
    //         ->groupBy('departemen.nama')
    //         ->orderByDesc('total_biaya')
    //         ->limit(10)
    //         ->get();

    //     $topTransportasi = DB::table('surat_perjalanan_dinas as spd')
    //         ->join('deklarasi_perjalanan_dinas as dpd', 'spd.id', '=', 'dpd.spd_id')
    //         ->select('spd.jenis_transport', 'spd.nama_transport', DB::raw('SUM(dpd.total_biaya) as total_biaya'))
    //         ->groupBy('spd.jenis_transport', 'spd.nama_transport')
    //         ->orderByDesc('total_biaya')
    //         ->limit(10)
    //         ->get();


    //     $periodes = PeriodeAnggaran::orderBy('created_at', 'desc')->take(3)->get();
    //     return view('dashboard.tmhcm', compact('periodes', 'topKaryawan', 'topDepartemen', 'topTransportasi'));
    // }

    public function dashboard(Request $request)
    {
        // Query untuk filter berdasarkan periode_id
        $query = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->join('departemen', 's.departemen_id', '=', 'departemen.id')
            ->select('s.nama_pegawai', 'departemen.nama as nama_departemen', DB::raw('SUM(d.total_biaya) as total_biaya'))
            ->groupBy('s.nama_pegawai', 'departemen.nama')
            ->orderByDesc('total_biaya')
            ->limit(10);

        // Jika ada periode_id yang dipilih, tambahkan filter
        if ($request->filled('periode_id')) {
            $query->where('s.periode_id', $request->periode_id);
        }

        // Ambil data topKaryawan
        $topKaryawan = $query->get();

        // Query untuk topDepartemen
        $topDepartemen = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->join('departemen', 's.departemen_id', '=', 'departemen.id')
            ->select('departemen.nama', DB::raw('SUM(d.total_biaya) as total_biaya'))
            ->groupBy('departemen.nama')
            ->orderByDesc('total_biaya')
            ->limit(10);

        // Filter berdasarkan periode_id
        if ($request->filled('periode_id')) {
            $topDepartemen->where('s.periode_id', $request->periode_id);
        }

        $topDepartemen = $topDepartemen->get();

        // Query untuk topTransportasi
        $topTransportasi = DB::table('surat_perjalanan_dinas as spd')
            ->join('deklarasi_perjalanan_dinas as dpd', 'spd.id', '=', 'dpd.spd_id')
            ->select('spd.jenis_transport', 'spd.nama_transport', DB::raw('SUM(dpd.total_biaya) as total_biaya'))
            ->groupBy('spd.jenis_transport', 'spd.nama_transport')
            ->orderByDesc('total_biaya')
            ->limit(10);

        // Filter berdasarkan periode_id
        if ($request->filled('periode_id')) {
            $topTransportasi->where('spd.periode_id', $request->periode_id);
        }

        $topTransportasi = $topTransportasi->get();

        // Ambil periode untuk filter dropdown
        $periodes = PeriodeAnggaran::orderBy('created_at', 'desc')->take(3)->get();

        return view('dashboard.tmhcm', compact('periodes', 'topKaryawan', 'topDepartemen', 'topTransportasi'));
    }
}

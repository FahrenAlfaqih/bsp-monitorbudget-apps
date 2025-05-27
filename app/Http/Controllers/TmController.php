<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodeAnggaran;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;


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
        // Ambil user dan filter periode jika ada
        $periodeId = $request->get('periode_id');

        // 1. Line chart: trend anggaran perjalanan dinas departemen dari tahun ke tahun atau per periode
        $trendAnggaran = DB::table('rancangan_anggaran as r')
            ->join('departemen as d', 'r.departemen_id', '=', 'd.id')
            ->join('periode_anggaran as p', 'r.periode_id', '=', 'p.id')
            ->select('p.nama_periode', DB::raw('SUM(r.jumlah_anggaran) as total_anggaran'))
            ->groupBy('p.nama_periode')
            ->orderBy('p.nama_periode')
            ->get();

        // 2. Pie chart: progress penggunaan anggaran dinas per departemen
        $progressAnggaran = DB::table('departemen as d')
            ->leftJoin('rancangan_anggaran as r', function ($join) use ($periodeId) {
                $join->on('d.id', '=', 'r.departemen_id');
                if ($periodeId) {
                    $join->where('r.periode_id', $periodeId);
                }
            })
            ->leftJoin('surat_perjalanan_dinas as spd', 'd.id', '=', 'spd.departemen_id')
            ->leftJoin('deklarasi_perjalanan_dinas as dpd', 'spd.id', '=', 'dpd.spd_id')
            ->select(
                'd.nama as nama_departemen',
                DB::raw('COALESCE(SUM(r.jumlah_anggaran), 0) as total_anggaran'),
                DB::raw('COALESCE(SUM(dpd.total_biaya), 0) as total_realisasi')
            )
            ->when($periodeId, function ($query) use ($periodeId) {
                return $query->where('spd.periode_id', $periodeId);
            })
            ->groupBy('d.nama')
            ->get();

        // 3. Bar chart: penggunaan anggaran perjalanan dinas tertinggi dari semua departemen
        $topDepartemen = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->join('departemen', 's.departemen_id', '=', 'departemen.id')
            ->select('departemen.nama', DB::raw('SUM(d.total_biaya) as total_biaya'))
            ->groupBy('departemen.nama')
            ->orderByDesc('total_biaya')
            ->limit(10);

        if ($periodeId) {
            $topDepartemen->where('s.periode_id', $periodeId);
        }

        $topDepartemen = $topDepartemen->get();

        $topTransportasi = DB::table('surat_perjalanan_dinas as spd')
            ->join('deklarasi_perjalanan_dinas as dpd', 'spd.id', '=', 'dpd.spd_id')
            ->select('spd.jenis_transport', 'spd.nama_transport', DB::raw('SUM(dpd.total_biaya) as total_biaya'))
            ->groupBy('spd.jenis_transport', 'spd.nama_transport')
            ->orderByDesc('total_biaya')
            ->limit(10);

        if ($request->filled('periode_id')) {
            $topTransportasi->where('spd.periode_id', $request->periode_id);
        }

        $topTransportasi = $topTransportasi->get();

        // 4. Bar chart: karyawan dengan biaya tertinggi
        $topKaryawan = DB::table('deklarasi_perjalanan_dinas as d')
            ->join('surat_perjalanan_dinas as s', 'd.spd_id', '=', 's.id')
            ->join('departemen', 's.departemen_id', '=', 'departemen.id')
            ->select('s.nama_pegawai', 'departemen.nama as nama_departemen', DB::raw('SUM(d.total_biaya) as total_biaya'))
            ->groupBy('s.nama_pegawai', 'departemen.nama')
            ->orderByDesc('total_biaya')
            ->limit(10);

        if ($periodeId) {
            $topKaryawan->where('s.periode_id', $periodeId);
        }

        $topKaryawan = $topKaryawan->get();

        // -- Tambahan: Data perbandingan anggaran fix per departemen per periode --
        $dataAnggaranPerDeptPerPeriode = DB::table('anggaran_fix as a')
            ->join('departemen as d', 'a.departemen_id', '=', 'd.id')
            ->join('periode_anggaran as p', 'a.periode_id', '=', 'p.id')
            ->select('p.nama_periode', 'd.nama as nama_departemen', DB::raw('SUM(a.jumlah_anggaran) as total_anggaran'))
            ->groupBy('p.nama_periode', 'd.nama')
            ->orderBy('p.nama_periode')
            ->orderBy('d.nama')
            ->get();

        $periodes = $dataAnggaranPerDeptPerPeriode->pluck('nama_periode')->unique()->values()->all();
        $departemens = $dataAnggaranPerDeptPerPeriode->pluck('nama_departemen')->unique()->values()->all();

        $datasets = [];

        // Fungsi warna statis
        $colors = ['#2563eb', '#4ade80', '#fbbf24', '#f87171', '#a78bfa', '#f472b6', '#60a5fa', '#fcd34d', '#f97316', '#c084fc'];

        foreach ($departemens as $index => $dept) {
            $datasetData = [];
            foreach ($periodes as $periode) {
                $found = $dataAnggaranPerDeptPerPeriode->firstWhere(function ($item) use ($periode, $dept) {
                    return $item->nama_periode === $periode && $item->nama_departemen === $dept;
                });
                $datasetData[] = $found ? (float) $found->total_anggaran : 0;
            }

            $datasets[] = [
                'label' => $dept,
                'data' => $datasetData,
                'backgroundColor' => $colors[$index % count($colors)],
            ];
        }

        // Ambil periode untuk filter dropdown juga
        $periodesDropdown = PeriodeAnggaran::orderBy('created_at', 'desc')->take(5)->get();

        // Cek pengajuan menunggu (optional)
        $jumlahMenunggu = DB::table('rancangan_anggaran')
            ->where('status', 'menunggu')
            ->count();
        if ($jumlahMenunggu > 0) {
            Alert::warning('Perhatian', 'Ada ' . $jumlahMenunggu . ' Pengajuan yang belum diproses. Silakan periksa kembali.');
        }

        return view('dashboard.tmhcm', compact(
            'periodesDropdown',
            'trendAnggaran',
            'progressAnggaran',
            'topDepartemen',
            'topKaryawan',
            'periodeId',
            'topTransportasi',
            'periodes', 
            'datasets' 
        ));
    }



    private function randomColor()
    {
        $colors = ['#2563eb', '#4ade80', '#fbbf24', '#f87171', '#a78bfa', '#f472b6', '#60a5fa', '#fcd34d', '#f97316', '#c084fc'];
        return $colors[array_rand($colors)];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\RancanganAnggaran;
use App\Models\PeriodeAnggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Events\AnggaranDisetujui;

class RancanganAnggaranController extends Controller
{
    public function index(Request $request)
    {
        $periodeList = PeriodeAnggaran::all();
        $departemenList = Departemen::all();

        if (Auth::user()->role === 'tmhcm') {
            $query = RancanganAnggaran::with(['departemen', 'periode']);

            if ($request->filled('departemen')) {
                $query->where('departemen_id', $request->departemen);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        } else {
            $query = RancanganAnggaran::with('periode')
                ->where('departemen_id', Auth::user()->departemen->id);
        }

        if ($request->filled('periode')) {
            $query->where('periode_id', $request->periode);
        }

        $rancangan = $query->get();

        return view('rancangan.index', [
            'rancangan' => $rancangan,
            'periodeList' => $periodeList,
            'departemenList' => $departemenList,
            'selectedPeriode' => $request->periode,
            'selectedDepartemen' => $request->departemen,
        ]);
    }

    public function create()
    {
        $periodeAktif = PeriodeAnggaran::where('status', 'dibuka')->first();

        if (!$periodeAktif) {
            Alert::warning('Perhatian', 'Tidak ada periode anggaran yang aktif.');
            return redirect()->route('rancangan.index');
        }

        return view('rancangan.create', [
            'periode' => [$periodeAktif],
            'selectedPeriodeId' => $periodeAktif->id
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_anggaran,id',
            'jumlah_anggaran' => 'required|integer|min:0'
        ]);

        RancanganAnggaran::create([
            'departemen_id' => Auth::user()->departemen->id,
            'periode_id' => $request->periode_id,
            'jumlah_anggaran' => $request->jumlah_anggaran,
            'status' => 'menunggu',
        ]);

        Alert::success('Berhasil', 'Pengajuan rancangan anggaran berhasil.');
        return redirect()->route('rancangan.index');
    }

    public function edit($id)
    {
        $rancangan = RancanganAnggaran::findOrFail($id);
        $periode = PeriodeAnggaran::all();
        return view('rancangan.update', compact('rancangan', 'periode'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_anggaran,id',
            'jumlah_anggaran' => 'required|integer|min:0'
        ]);

        $rancangan = RancanganAnggaran::findOrFail($id);
        $rancangan->update([
            'periode_id' => $request->periode_id,
            'jumlah_anggaran' => $request->jumlah_anggaran,
            'status' => 'menunggu'
        ]);

        Alert::success('Berhasil', 'Rancangan anggaran berhasil diperbarui.');
        return redirect()->route('rancangan.index');
    }

    public function editStatus($id)
    {
        $rancangan = RancanganAnggaran::findOrFail($id);
        return view('rancangan.update-status', compact('rancangan'));
    }

    public function updateStatus(Request $request, $id)
    {

        $rancangan = RancanganAnggaran::findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak',
            'catatan' => $request->status === 'ditolak' ? 'required|string' : 'nullable|string'
        ]);

        $rancangan->update([
            'status' => $request->status,
            'catatan' => $request->catatan
        ]);

        if ($request->status === 'disetujui') {
            event(new AnggaranDisetujui($rancangan));
        }

        Alert::success('Berhasil', 'Status berhasil diperbarui.');
        return redirect()->route('rancangan.index');
    }
}

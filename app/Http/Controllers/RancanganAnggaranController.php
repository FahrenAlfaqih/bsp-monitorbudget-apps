<?php

namespace App\Http\Controllers;

use App\Events\AnggaranDisetujui;
use App\Models\Departemen;
use App\Models\RancanganAnggaran;
use App\Models\PeriodeAnggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        } else {
            $query = RancanganAnggaran::with('periode')
                ->where('departemen_id', Auth::user()->departemen->id);
        }

        if ($request->filled('periode')) {
            $query->where('periode_id', $request->periode);
        }

        $rancangan = $query->get();

        return view('rancangan.index', compact('rancangan', 'periodeList', 'departemenList'));
    }

    public function create(Request $request)
    {
        $periode = PeriodeAnggaran::all();
        $selectedPeriodeId = $request->periode_id;
        return view('rancangan.create', compact('periode', 'selectedPeriodeId'));
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

        return redirect()->route('rancangan.index')->with('success', 'Pengajuan rancangan anggaran berhasil.');
    }

    public function edit($id)
    {
        $rancangan = RancanganAnggaran::findOrFail($id);
        $periode = PeriodeAnggaran::all();
        return view('rancangan.edit', compact('rancangan', 'periode'));
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak',
            'catatan' => 'nullable|string'
        ]);

        $rancangan = RancanganAnggaran::findOrFail($id);
        $rancangan->update([
            'status' => $request->status,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('rancangan.index')->with('success', 'Status berhasil diperbarui.');
    }
}

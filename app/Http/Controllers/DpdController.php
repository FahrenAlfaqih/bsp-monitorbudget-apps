<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Dpd;
use App\Models\Spd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DpdController extends Controller
{
    /**
     * @desc ini fungsi index
     *
     * @return void
     */
    public function create()
    {
        $spds = Spd::all();
        return view('dpd.create', compact('spds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'spd_id' => 'required|exists:surat_perjalanan_dinas,id',
            'total_biaya' => 'required|numeric',
            'tanggal_deklarasi' => 'required|date',
            'uraian' => 'nullable|string',
        ]);

        Dpd::create([
            'spd_id' => $request->spd_id,
            'user_id' => Auth::id(),
            'total_biaya' => $request->total_biaya,
            'tanggal_deklarasi' => $request->tanggal_deklarasi,
            'uraian' => $request->uraian,
        ]);
        return redirect()->route('dpd.index')->with('success', 'Deklarasi perjalanan dinas berhasil disimpan.');
    }

    public function index()
    {
        $deklarasi = Dpd::all();
        return view('dpd.index', compact('deklarasi'));
    }

    public function edit($id)
    {
        $deklarasi = Dpd::findOrFail($id);
        $spds = Spd::all();
        return view('deklarasi_perjalanan_dinas.edit', compact('deklarasi', 'spds'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'spd_id' => 'required|exists:surat_perjalanan_dinas,id',
            'total_biaya' => 'required|numeric',
            'tanggal_deklarasi' => 'required|date',
            'uraian' => 'nullable|string',
        ]);

        $deklarasi = Dpd::findOrFail($id);
        $deklarasi->update([
            'spd_id' => $request->spd_id,
            'user_id' => Auth::id(),
            'total_biaya' => $request->total_biaya,
            'tanggal_deklarasi' => $request->tanggal_deklarasi,
            'uraian' => $request->uraian,
        ]);

        return redirect()->route('dpd.index')->with('success', 'Deklarasi perjalanan dinas berhasil diperbarui.');
    }

    // Menghapus data deklarasi perjalanan dinas
    public function destroy($id)
    {
        $deklarasi = Dpd::findOrFail($id);
        $deklarasi->delete();

        return redirect()->route('dpd.index')->with('success', 'Deklarasi perjalanan dinas berhasil dihapus.');
    }
}

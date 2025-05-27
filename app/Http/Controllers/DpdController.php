<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Dpd;
use App\Models\Spd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;


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
            'tanggal_deklarasi'
            => 'required|date',
            'uraian' => 'nullable|string',
        ]);

        Dpd::create([
            'spd_id' => $request->spd_id,
            'user_id' => Auth::id(),
            'total_biaya' => $request->total_biaya,
            'pr' => $request->pr,
            'po' => $request->po,
            'ses' => $request->ses,
            'tanggal_deklarasi' => $request->tanggal_deklarasi,
            'uraian' => $request->uraian,
        ]);
        return redirect()->route('dpd.index')->with('success', 'Deklarasi perjalanan dinas berhasil disimpan.');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $departemen = DB::table('departemen')->where('user_id', $user->id)->first();

        $query = DB::table('deklarasi_perjalanan_dinas')
            ->join('surat_perjalanan_dinas as spd', 'deklarasi_perjalanan_dinas.spd_id', '=', 'spd.id')
            ->join('departemen', 'spd.departemen_id', '=', 'departemen.id')
            ->select(
                'deklarasi_perjalanan_dinas.*',
                'spd.nomor_spd',
                'spd.nama_pegawai',
                'spd.tanggal_berangkat',
                'departemen.nama as departemen_nama',
                'spd.id as spd_id'
            );

        
        if ($user->role === 'admindept') {
            if ($departemen) {
                $query->where('departemen.id', $departemen->id);
            } else {
                $query->whereRaw('0=1');
            }
        }

        // Filter berdasarkan input user
        if ($request->filled('nama_pegawai')) {
            $query->where('spd.nama_pegawai', 'like', '%' . $request->nama_pegawai . '%');
        }
        if ($request->filled('departemen')) {
            $query->where('departemen.id', $request->departemen);
        }
        if ($request->filled('tanggal_deklarasi')) {
            $query->where('deklarasi_perjalanan_dinas.tanggal_deklarasi', $request->tanggal_deklarasi);
        }
        if ($request->filled('tgl_dinas_awal') && $request->filled('tgl_dinas_akhir')) {
            $query->whereBetween('spd.tanggal_berangkat', [$request->tgl_dinas_awal, $request->tgl_dinas_akhir]);
        } elseif ($request->filled('tgl_dinas_awal')) {
            $query->where('spd.tanggal_berangkat', '>=', $request->tgl_dinas_awal);
        }

        $deklarasi = $query->get();

        $spdIds = $deklarasi->pluck('spd_id')->toArray();

        $details = DB::table('spd_detail')
            ->whereIn('spd_id', $spdIds)
            ->get()
            ->groupBy('spd_id');

        $departemenList = DB::table('departemen')->get();

        return view('dpd.index', compact('deklarasi', 'departemenList', 'details'));
    }



    public function show($id)
    {
        $dpd = Dpd::with('spd.details', 'spd.departemen')->findOrFail($id);

        return view('dpd.show', compact('dpd'));
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

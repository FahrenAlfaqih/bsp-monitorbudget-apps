<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Dpd;
use App\Models\Pegawai;
use App\Models\PeriodeAnggaran;
use App\Models\Spd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpdController extends Controller
{
    public function index()
    {
        $spds = Spd::with(['departemen', 'user'])->get();

        return view('spd.index', compact('spds'));
    }



    public function ajukan(Request $request)
    {
        $ids = $request->input('spd_ids');

        if (!$ids) {
            return back()->with('error', 'Pilih minimal satu SPD untuk diajukan.');
        }


        Spd::whereIn('id', $ids)->update(['status' => 'diajukan']);

        return back()->with('success', 'SPD berhasil diajukan ke finance.');
    }

    public function pengajuan()
    {

        $spds = Spd::where('status', 'diajukan')->get();
        return view('spd.pengajuan', compact('spds'));
    }

    public function editStatus(Spd $spd)
    {
        if ($spd->status !== 'diajukan') {
            return redirect()->route('spd.pengajuan')->with('error', 'SPD sudah diproses.');
        }

        return view('spd.update-status', compact('spd'));
    }

    public function updateStatus(Request $request, Spd $spd)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'total_biaya' => 'required_if:status,disetujui|nullable|numeric',
            'tanggal_deklarasi' => 'required|date',
            'uraian' => 'required|string',
        ]);

        $spd->update([
            'status' => $request->status,
            'uraian' => $request->uraian,
            'tanggal_deklarasi' => $request->tanggal_deklarasi,
        ]);

        if ($request->status === 'disetujui') {
            $dpdExists = Dpd::where('spd_id', $spd->id)->exists();

            if (!$dpdExists) {
                Dpd::create([
                    'spd_id' => $spd->id,
                    'user_id' => Auth::id(),
                    'total_biaya' => $request->total_biaya,
                    'tanggal_deklarasi' => $request->tanggal_deklarasi,
                    'uraian' => $request->uraian,
                ]);
            }
        }

        return redirect()->route('spd.pengajuan')->with('success', 'Status SPD berhasil diupdate.');
    }





    public function create()
    {
        $departemen = Departemen::all();
        $users = User::all();
        $pegawais = Pegawai::all();
        $periodes = PeriodeAnggaran::all(); 
    
        return view('spd.create', compact('departemen', 'users', 'pegawais', 'periodes'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'departemen_id' => 'required|exists:departemen,id',
            'pegawai_id' => 'required|exists:pegawai,id',
            'periode_id' => 'required|exists:periode_anggaran,id',
            'nomor_spd' => 'required|unique:surat_perjalanan_dinas,nomor_spd',
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'kegiatan' => 'nullable|string',
            'tanggal_berangkat' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_berangkat',
            'jenis_transport' => 'nullable|string',
            'nama_transport' => 'nullable|string',
        ]);

        $pegawai = Pegawai::find($validated['pegawai_id']);

        Spd::create([
            'departemen_id' => $validated['departemen_id'],
            'pegawai_id' => $pegawai->id,
            'periode_id' => $validated['periode_id'],
            'user_id' => Auth::id(),
            'nomor_spd' => $validated['nomor_spd'],
            'nama_pegawai' => $pegawai->nama_pegawai, 
            'asal' => $validated['asal'],
            'tujuan' => $validated['tujuan'],
            'kegiatan' => $validated['kegiatan'],
            'tanggal_berangkat' => $validated['tanggal_berangkat'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'jenis_transport' => $validated['jenis_transport'],
            'nama_transport' => $validated['nama_transport'],
        ]);

        return redirect()->route('spd.index')->with('success', 'Surat Perjalanan Dinas berhasil ditambahkan!');
    }
}

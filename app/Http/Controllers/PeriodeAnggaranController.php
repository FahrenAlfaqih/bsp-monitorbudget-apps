<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAnggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class PeriodeAnggaranController extends Controller
{
    public function index()
    {
        PeriodeAnggaran::autoUpdateStatus();

        $periodes = PeriodeAnggaran::orderBy('created_at', 'desc')->get();
        return view('periode_anggaran.index', compact('periodes'));
    }


    public function create()
    {
        return view('periode_anggaran.create');
    }

    public function store(Request $request)
    {
        $tahun = Carbon::parse($request->mulai)->year;
        $periodeExist = PeriodeAnggaran::whereYear('mulai', $tahun)->exists();

        if ($periodeExist) {
            Alert::error('Gagal', 'Periode anggaran untuk tahun ' . $tahun . ' sudah ada!');
            return redirect()->route('periode.index');
        }

        try {
            $request->validate([
                'nama_periode' => 'required|string|max:255',
                'mulai' => 'required|date',
                'berakhir' => 'required|date|after_or_equal:mulai',
            ]);

            PeriodeAnggaran::create([
                'nama_periode' => $request->nama_periode,
                'mulai' => $request->mulai,
                'berakhir' => $request->berakhir,
                'status' => 'dibuka',
                'user_id' => Auth::id(),
            ]);

            Alert::success('Berhasil', 'Periode anggaran berhasil dibuka!');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data!');
        }
        return redirect()->route('periode.index');
    }

    public function handleEdit(Request $request)
    {
        $periodeId = $request->input('periode_id');
        $periode = PeriodeAnggaran::findOrFail($periodeId);
        return view('periode_anggaran.update', compact('periode'));
    }

    public function edit()
    {
        $periodeId = session('periode_id');
        $periode = PeriodeAnggaran::findOrFail($periodeId);
        return view('periode_anggaran.update', compact('periode'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_periode' => 'required|string|max:255',
                'mulai' => 'required|date',
                'berakhir' => 'required|date|after_or_equal:mulai',
            ]);

            $periode = PeriodeAnggaran::findOrFail($id);
            $periode->update([
                'nama_periode' => $request->nama_periode,
                'mulai' => $request->mulai,
                'berakhir' => $request->berakhir,
            ]);

            Alert::success('Berhasil', 'Data periode berhasil diperbarui!');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat memperbarui data!');
        }

        return redirect()->route('periode.index');
    }
}

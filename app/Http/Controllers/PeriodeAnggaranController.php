<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAnggaran;
use Illuminate\Http\Request;

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
        ]);

        return redirect()->route('periode.index')->with('success', 'Periode anggaran berhasil dibuka');
    }
}

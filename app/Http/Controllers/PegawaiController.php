<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::all();
        return view('pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    /**
     * @desc fungsi untuk menyimpan data pegawai dan menyimpan log sebagai proses debugging
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        Log::info('Masuk ke PegawaiController@store', $request->all());

        try {
            $validated = $request->validate([
                'nopekerja' => 'required|string|max:50|unique:pegawai,nopekerja',
                'nama_pegawai' => 'required|string|max:255',
                'email' => 'required|email|unique:pegawai,email',
            ]);

            $pegawai = Pegawai::create($validated);
            Log::info('Pegawai berhasil dibuat:', $pegawai->toArray());

            return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pegawai: ' . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        Log::info('Masuk ke update:', ['pegawai_id' => $pegawai->id]);

        try {
            $validated = $request->validate([
                'nopekerja' => 'required|string|unique:pegawai,nopekerja,' . $pegawai->id,
                'nama_pegawai' => 'required|string|max:255',
                'email' => 'nullable|string|max:255',
            ]);

            $pegawai->update($validated);
            Log::info('Data pegawai berhasil diperbarui:', $pegawai->toArray());

            return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update pegawai: ' . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan saat memperbarui data.')->withInput();
        }
    }
}

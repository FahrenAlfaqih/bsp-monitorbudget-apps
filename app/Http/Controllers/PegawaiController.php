<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;


class PegawaiController extends Controller
{
    /**
     * @desc ini adalah fungsi index, untuk menampilkan
     *  halaman index pegawai dan mengirim keseluruhan data pegawai
     *
     * @return void
     */
    public function index()
    {
        $pegawais = Pegawai::all();
        return view('pegawai.index', compact('pegawais'));
    }
    public function indexTest()
    {
        $pegawais = Pegawai::all();
        return view('pegawai.index', compact('pegawais'));
    }

    /**
     * @desc fungsi mengembalikan halaman create pegawai jika ingin menambahkan data pegawai
     *
     * @return void
     */
    public function create()
    {
        return view('pegawai.create');
    }

    /**
     * @desc fungsi untuk menyimpan data pegawai dan menyimpan log serta try catch sebagai proses debugging 
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
            Alert::success('Berhasil', 'Data pegawai berhasil ditambahkan!');
            return redirect()->route('pegawai.index');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pegawai: ' . $e->getMessage());
            Alert::error('Gagal menyimpan pegawai', 'Terjadi kesalahan saat menyimpan data!');
            return back()->withInput();
        }
    }

    /**
     * @desc function untuk mengarahkan ke halaman edit pegawai ketika aksi edit dipilih
     *
     * @param Pegawai $pegawai
     * @return void akan mengembalikan data pegawai yang dipilih ke views pegawai/edit
     */
    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    /**
     * @desc function untuk update data pegawai, menggunakan debugging log() dan try catch untuk menangkap error nantinya
     * sehingga aplikasi tidak crash ketika adanya eror saat update data
     *
     * @param Request $request
     * @param Pegawai $pegawai
     * @return void
     */
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

            Alert::success('Berhasil', 'Data pegawai berhasil diperbarui!');
            return redirect()->route('pegawai.index');
        } catch (\Exception $e) {
            Log::error('Gagal update pegawai: ' . $e->getMessage());

            Alert::error('Gagal', 'Terjadi kesalahan saat memperbarui data!');
            return back()->withInput();
        }
    }
}

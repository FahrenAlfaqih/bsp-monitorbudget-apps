<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;



class DepartemenController extends Controller
{
    /**
     * Menampilkan data departemen.
     *
     * @desc Mengambil data departemen beserta relasi user yang terkait.
     * @return \Illuminate\View\View Mengembalikan view dengan data departemen.
     */
    public function index()
    {
        $departemen = Departemen::with('user')->get();

        return view('departemen.index', compact('departemen'));
    }

    /**
     * Menampilkan halaman untuk membuat departemen baru.
     *
     * @desc Menampilkan form untuk membuat departemen baru.
     * @return \Illuminate\View\View Mengembalikan view untuk form create departemen.
     */
    public function create()
    {
        return view('departemen.create');
    }

    /**
     * Menyimpan data departemen beserta user terkait.
     *
     * @desc Menyimpan data departemen dan membuat user baru dengan role 'admindept'.
     * @param \Illuminate\Http\Request $request Data yang diterima untuk disimpan.
     * @return \Illuminate\Http\RedirectResponse Mengarahkan kembali ke halaman index departemen.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'bs_number' => 'required',
        ]);

        // Simpan ke tabel users
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admindept',
        ]);

        // Simpan ke tabel departemen
        Departemen::create([
            'user_id' => $user->id,
            'bs_number' => $request->bs_number,
            'nama' => $request->nama,
            'email' => $request->email,
        ]);
        Alert::success('Berhasil', 'Departemen berhasil ditambahkan, harap setiap admin departemen untuk mengganti password default!');
        return redirect()->route('departemen.index');
    }

    /**
     * Menampilkan halaman untuk mengedit data departemen.
     *
     * @desc Menampilkan form untuk mengedit departemen dengan data yang sudah ada.
     * @param \App\Models\Departemen $departemen Departemen yang akan diedit.
     * @return \Illuminate\View\View Mengembalikan view untuk form edit departemen.
     */
    public function edit(Departemen $departemen)
    {
        return view('departemen.edit', compact('departemen'));
    }

    /**
     * Mengupdate data departemen.
     *
     * @desc Mengupdate data departemen berdasarkan data yang diberikan.
     * @param \Illuminate\Http\Request $request Data yang diterima untuk diupdate.
     * @param \App\Models\Departemen $departemen Departemen yang akan diupdate.
     * @return \Illuminate\Http\RedirectResponse Mengarahkan kembali ke halaman index departemen.
     */
    public function update(Request $request, Departemen $departemen)
    {
        $validated = $request->validate([
            'nama' => 'required|string|unique:departemen,nama,' . $departemen->id,
            'bs_number' => 'required|string|max:255',
        ]);

        $departemen->update($validated);
        Alert::success('Berhasil', 'Data Departemen berhasil diperbarui!');
        return redirect()->route('departemen.index');
    }
}

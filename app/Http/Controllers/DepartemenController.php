<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DepartemenController extends Controller
{
    /**
     * @desc fungsi index untuk mengambil data Departemen
     * @return void mengirim data departemen ke views departemen/index
     */
    public function index()
    {
        $departemen = Departemen::with('user')->get();

        return view('departemen.index', compact('departemen'));
    }

    /**
     * @desc fungsi ini berfungsi untuk mengarahkan ke halaman create departemen data
     * @return void akan return ke views/departemen/create
     */
    public function create()
    {
        return view('departemen.create');
    }

    /**
     * @desc fungsi ini untuk menyimpan data yang diterima dari halaman departemen.create dan sekaligus menyimpan data users, ketika menyimpan departemen maka otomatis 
     * menyimpannya sebagai user, sehingga user tersebut bisa login sebagai admin departemen
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'bs_number' => 'required',
        ]);

        // Simpan ke users
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admindept',
        ]);

        Departemen::create([
            'user_id' => $user->id,
            'bs_number' => $request->bs_number,
            'nama' => $request->nama,
            'email' => $request->email,
        ]);


        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil ditambahkan');
    }

    /**
     * @desc fungsi ini untuk mengarahkan ke halaman departemen.edit dan mengirim data dari departemen yang ingin diedit
     *
     * @param Departemen $departemen
     * @return void
     */
    public function edit(Departemen $departemen)
    {
        return view('departemen.edit', compact('departemen'));
    }

    /**
     * @desc fungsi update ini untuk mengupdate data departemen
     * @param Request $request
     * @param Departemen $departemen untuk nantinya mengambil departemen id
     * @return void
     */
    public function update(Request $request, Departemen $departemen)
    {
        $validated = $request->validate([
            'nama' => 'required|string|unique:departemen,nama,' . $departemen->id,
            'bs_number' => 'required|string|max:255',
        ]);

        $departemen->update($validated);

        return redirect()->route('departemen.index')->with('success', 'Data Departemen berhasil diperbarui.');
    }
}

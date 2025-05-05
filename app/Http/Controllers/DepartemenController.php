<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DepartemenController extends Controller
{
    public function index()
    {
        $departemen = Departemen::with('user')->get();

        return view('departemen.index', compact('departemen'));
    }

    public function create()
    {
        return view('departemen.create');
    }

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

    public function edit(Departemen $departemen)
    {
        return view('departemen.edit', compact('departemen'));
    }

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

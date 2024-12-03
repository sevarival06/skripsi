<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Fungsi untuk menampilkan form edit profil
    public function edit($id)
{
    $user = User::find($id); // Mendapatkan data pengguna berdasarkan ID
    return view('profil.edit', compact('user')); // Menampilkan view dengan data pengguna
}



    // Fungsi untuk menyimpan perubahan profil
    public function update(Request $request, $id)
    {
        // Validasi inputan form
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'alamat' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Update data user
        $user->nama_usaha = $request->input('nama_usaha');
        $user->email = $request->input('email');
        $user->alamat = $request->input('alamat');
        $user->username = $request->input('username');

        // Jika password diisi, maka ubah password
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Simpan perubahan
        $user->save();

        // Redirect ke halaman daftar user dengan pesan sukses
        return redirect()->route('user.index')->with('success', 'Profil berhasil diperbarui');
    }
}

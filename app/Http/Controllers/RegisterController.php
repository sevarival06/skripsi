<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Menampilkan halaman pendaftaran
    public function index()
    {
        return view('auth.register');
    }

    // Menangani proses pendaftaran pengguna
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_usaha' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan data pengguna dengan role default 'user'
        User::create([
            'nama_usaha' => $request->nama_usaha,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'user', // Menambahkan role secara otomatis
        ]);

        // Redirect ke halaman login setelah berhasil daftar
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }
}

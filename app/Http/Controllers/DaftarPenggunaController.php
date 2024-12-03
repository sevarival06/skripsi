<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DaftarPenggunaController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('daftar_pengguna.index', [
            'users' => $users,
            'title' => 'Daftar Pengguna'
        ]);
    }

    public function create()
    {
        return view('daftar_pengguna.create', [
            'title' => 'Tambah Pengguna'
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_usaha' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'nama_usaha' => $request->nama_usaha,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('daftar_pengguna.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('daftar_pengguna.edit', [
            'user' => $user,
            'title' => 'Edit Pengguna'
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'nama_usaha' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id_user.',id_user',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id_user.',id_user',
            'role' => 'required|in:admin,user',
        ];

        if($request->filled('password')) {
            $rules['password'] = 'required|string|min:6';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userData = [
            'nama_usaha' => $request->nama_usaha,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
        ];

        if($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('daftar_pengguna.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('daftar_pengguna.index')->with('success', 'Pengguna berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('daftar_pengguna.index')->with('error', 'Gagal menghapus pengguna!');
        }
    }
}
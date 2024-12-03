<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class EditProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('edit_profil', [
            'user' => $user,
            'title' => 'Edit Profil'
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validasi input dengan menggunakan primary key id_user
        $rules = [
            'nama_usaha' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id_user.',id_user',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id_user.',id_user',
        ];

        // Jika password diisi, tambahkan validasi password
        if($request->filled('password')) {
            $rules['password'] = 'required|string|min:6';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update data user
            $userData = [
                'nama_usaha' => $request->nama_usaha,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'username' => $request->username,
            ];

            // Update password jika diisi
            if($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            // Update user berdasarkan id_user
            User::where('id_user', $user->id_user)->update($userData);

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }
}
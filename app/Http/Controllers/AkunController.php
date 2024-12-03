<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $dataAkun = Akun::where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('data_akun', [
            'dataAkun' => $dataAkun,
            'title' => 'Data Akun'
        ]);
    }

    public function create()
    {
        $title = 'Tambah Data Akun';
        $titleTag = 'Tambah Data Akun';
        $action = route('akun.store');

        return view('form_akun', compact('title', 'titleTag', 'action'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'no_reff' => [
                'required', 
                'unique:akun,no_reff,NULL,id,id_user,' . Auth::id()
            ],
            'nama_reff' => [
                'required', 
                'unique:akun,nama_reff,NULL,id,id_user,' . Auth::id()
            ],
            'keterangan' => 'required|max:255'
        ]);

        try {
            $akun = new Akun();
            $akun->no_reff = $request->no_reff;
            $akun->nama_reff = $request->nama_reff;
            $akun->keterangan = $request->keterangan;
            $akun->id_user = Auth::id(); // Pastikan id_user tersimpan
            $akun->save();

            Session::flash('berhasil', 'Data Akun Berhasil Ditambahkan');
            return redirect()->route('akun.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Gagal menambahkan data akun: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($no_reff)
    {
        // Pastikan user hanya bisa mengedit akun miliknya sendiri
        $akun = Akun::where('no_reff', $no_reff)
                    ->where('id_user', Auth::id())
                    ->firstOrFail();

        return view('form_akun', [
            'title' => 'Edit Data Akun',
            'titleTag' => 'Edit Data Akun',
            'action' => route('akun.update', ['no_reff' => $no_reff]),
            'data' => $akun
        ]);
    }

    public function update(Request $request, $no_reff)
    {
        // Pastikan akun yang akan diupdate milik user yang sedang login
        $akun = Akun::where('no_reff', $no_reff)
                    ->where('id_user', Auth::id())
                    ->firstOrFail();

        // Validasi input yang sudah diperbaiki
        $this->validate($request, [
            'no_reff' => [
                'required',
                Rule::unique('akun')->where(function ($query) {
                    return $query->where('id_user', Auth::id());
                })->ignore($no_reff, 'no_reff')
            ],
            'nama_reff' => [
                'required',
                Rule::unique('akun')->where(function ($query) {
                    return $query->where('id_user', Auth::id());
                })->ignore($no_reff, 'no_reff')
            ],
            'keterangan' => 'required|max:255'
        ]);

        try {
            $akun->no_reff = $request->no_reff;
            $akun->nama_reff = $request->nama_reff;
            $akun->keterangan = $request->keterangan;
            $akun->save();

            Session::flash('berhasil', 'Data Akun Berhasil Diubah');
            return redirect()->route('akun.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Gagal mengubah data akun: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function delete($no_reff)
    {
        // Pastikan user hanya bisa menghapus akun miliknya sendiri
        $akun = Akun::where('no_reff', $no_reff)
                    ->where('id_user', Auth::id())
                    ->firstOrFail();

        try {
            $akun->delete();

            Session::flash('berhasilHapus', 'Data akun dengan No.Reff '.$no_reff.' berhasil dihapus');
            return redirect()->route('akun.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Gagal menghapus data akun: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    // Hapus method isNamaAkunThere dan isNoAkunThere karena sudah digantikan 
    // oleh validasi unique pada saat store dan update
}
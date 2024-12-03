<?php

namespace App\Http\Controllers;

use App\Models\Ekuitas;
use Illuminate\Http\Request;
use App\Models\Akun;
use Illuminate\Support\Facades\Auth;

class EkuitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $listEkuitas = Ekuitas::where('id_user', Auth::id())
            ->orderByDesc('tgl_ekuitas')
            ->get();
        
        $tahun = Ekuitas::where('id_user', Auth::id())
            ->selectRaw('YEAR(tgl_ekuitas) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get();

        return view('ekuitas_main', [
            'listEkuitas' => $listEkuitas,
            'tahun' => $tahun,
            'title' => 'Ekuitas'
        ]);
    }

    public function detail(Request $request)
    {
        $month = $request->input('bulan');
        $year = $request->input('tahun');

        if (empty($month) || empty($year)) {
            return redirect()->route('ekuitas.index');
        }

        $ekuitas = Ekuitas::where('id_user', Auth::id())
            ->with('akun')
            ->whereMonth('tgl_ekuitas', $month)
            ->whereYear('tgl_ekuitas', $year)
            ->get();

        if ($ekuitas->isEmpty()) {
            return redirect()->route('ekuitas.index')
                ->with('error', 'Data Ekuitas Dengan Bulan ' . $this->getMonthName($month) . 
                    ' Pada Tahun ' . $year . ' Tidak Ditemukan');
        }

        $totalDebit = $ekuitas->where('jenis_saldo', 'debit')->sum('saldo');
        $totalKredit = $ekuitas->where('jenis_saldo', 'kredit')->sum('saldo');

        return view('ekuitas', [
            'ekuitas' => $ekuitas,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'title' => 'Ekuitas'
        ]);
    }

    public function create()
    {
        // Ambil daftar akun hanya untuk user yang sedang login
        $dropdownList = Akun::where('id_user', Auth::id())
            ->orderBy('nama_reff', 'asc')
            ->get(['no_reff', 'nama_reff']);

        return view('form_ekuitas', [
            'title' => 'Tambah Ekuitas',
            'data' => new Ekuitas(),
            'action' => route('ekuitas.store'),
            'dropdownList' => $dropdownList,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_reff' => [
                'required', 
                'exists:akun,nama_reff,id_user,'.Auth::id() // Tambahkan validasi user
            ],
            'tgl_ekuitas' => 'required|date',
            'jenis_saldo' => 'required|in:debit,kredit',
            'saldo' => 'required|numeric',
        ]);

        // Ambil no_reff berdasarkan nama_reff milik user yang sedang login
        $selectedAkun = Akun::where('nama_reff', $validated['nama_reff'])
            ->where('id_user', Auth::id())
            ->firstOrFail();

        // Buat array data untuk disimpan
        $dataToSave = [
            'no_reff' => $selectedAkun->no_reff,
            'tgl_ekuitas' => $validated['tgl_ekuitas'],
            'jenis_saldo' => $validated['jenis_saldo'],
            'saldo' => $validated['saldo'],
            'id_user' => Auth::id(),
            'tgl_input' => now(),
        ];

        // Simpan data ke tabel Ekuitas
        Ekuitas::create($dataToSave);

        return redirect()->route('ekuitas.index')
            ->with('success', 'Data Ekuitas Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $ekuitas = Ekuitas::where('id', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        // Ambil daftar akun hanya untuk user yang sedang login
        $dropdownList = Akun::where('id_user', Auth::id())
            ->orderBy('nama_reff', 'asc')
            ->get(['no_reff', 'nama_reff']);

        return view('form_ekuitas', [
            'title' => 'Edit Ekuitas',
            'data' => $ekuitas,
            'action' => route('ekuitas.update', $id),
            'dropdownList' => $dropdownList,
        ]);
    }

    public function update(Request $request, $id)
    {
        $ekuitas = Ekuitas::where('id', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();
    
        $validated = $request->validate([
            'nama_reff' => [
                'required', 
                'exists:akun,nama_reff,id_user,'.Auth::id() // Tambahkan validasi user
            ],
            'tgl_ekuitas' => 'required|date',
            'jenis_saldo' => 'required|in:debit,kredit',
            'saldo' => 'required|numeric',
        ]);

        $selectedAkun = Akun::where('nama_reff', $validated['nama_reff'])
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $ekuitas->update([
            'no_reff' => $selectedAkun->no_reff,
            'tgl_ekuitas' => $validated['tgl_ekuitas'],
            'jenis_saldo' => $validated['jenis_saldo'],
            'saldo' => $validated['saldo'],
            'id_user' => Auth::id(),
            'tgl_input' => now(),
        ]);

        return redirect()->route('ekuitas.index')
            ->with('success', 'Data Ekuitas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $ekuitas = Ekuitas::where('id_ekuitas', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $ekuitas->delete();
        return redirect()->route('ekuitas.index')
            ->with('success', 'Data Ekuitas berhasil dihapus');
    }
    
    // Metode AJAX untuk mendapatkan no_reff berdasarkan nama_reff
    public function getNoReff(Request $request)
    {
        $namaReff = $request->get('nama_reff');
        $akun = Akun::where('nama_reff', $namaReff)
            ->where('id_user', Auth::id())
            ->first();

        if ($akun) {
            return response()->json([
                'status' => 'success',
                'no_reff' => $akun->no_reff,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data akun tidak ditemukan',
        ], 404);
    }

    private function getMonthName($month)
    {
        return \Carbon\Carbon::create()->month($month)->locale('id')->monthName;
    }
}

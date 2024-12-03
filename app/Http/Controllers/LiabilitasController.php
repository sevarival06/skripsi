<?php

namespace App\Http\Controllers;

use App\Models\Liabilitas;
use Illuminate\Http\Request;
use App\Models\Akun;
use Illuminate\Support\Facades\Auth;

class LiabilitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Filter liabilitas by current user's ID
        $listLiabilitas = Liabilitas::where('id_user', Auth::id())->get();
        
        // Filter years based on current user's Liabilitas
        $tahun = Liabilitas::where('id_user', Auth::id())
            ->selectRaw('YEAR(tgl_liabilitas) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get();

        return view('liabilitas_main', [
            'listLiabilitas' => $listLiabilitas,
            'tahun' => $tahun,
            'title' => 'Liabilitas'
        ]);
    }

    public function detail(Request $request)
    {
        $month = $request->input('bulan');
        $year = $request->input('tahun');

        if (empty($month) || empty($year)) {
            return redirect()->route('liabilitas.index');
        }

        // Filter liabilitas by current user's ID, month, and year
        $liabilitas = Liabilitas::with('akun')
            ->where('id_user', Auth::id())
            ->whereMonth('tgl_liabilitas', $month)
            ->whereYear('tgl_liabilitas', $year)
            ->get();

        if ($liabilitas->isEmpty()) {
            return redirect()->route('liabilitas.index')
                ->with('error', 'Data Liabilitas Dengan Bulan ' . $this->getMonthName($month) . 
                    ' Pada Tahun ' . $year . ' Tidak Ditemukan');
        }

        $totalDebit = $liabilitas->where('jenis_saldo', 'debit')->sum('saldo');
        $totalKredit = $liabilitas->where('jenis_saldo', 'kredit')->sum('saldo');

        return view('liabilitas', [
            'liabilitas' => $liabilitas,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'title' => 'Liabilitas'
        ]);
    }

    public function create()
    {
        // Filter accounts by the current user's ID
        $dropdownList = Akun::where('id_user', Auth::id())
            ->get(['no_reff', 'nama_reff']);

        return view('form_liabilitas', [
            'title' => 'Tambah Liabilitas',
            'data' => new Liabilitas(),
            'action' => route('liabilitas.store'),
            'dropdownList' => $dropdownList,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_reff' => [
                'required', 
                'exists:akun,nama_reff,id_user,' . Auth::id() // Add user ID validation
            ],
            'tgl_liabilitas' => 'required|date',
            'jenis_saldo' => 'required|in:debit,kredit',
            'saldo' => 'required|numeric',
        ]);

        // Ambil no_reff berdasarkan nama_reff dan id_user
        $selectedAkun = Akun::where('nama_reff', $validated['nama_reff'])
            ->where('id_user', Auth::id())
            ->firstOrFail();

        // Buat array data untuk disimpan
        $dataToSave = [
            'no_reff' => $selectedAkun->no_reff,
            'tgl_liabilitas' => $validated['tgl_liabilitas'],
            'jenis_saldo' => $validated['jenis_saldo'],
            'saldo' => $validated['saldo'],
            'id_user' => Auth::id(),
            'tgl_input' => now(),
        ];

        // Simpan data ke tabel Liabilitas
        Liabilitas::create($dataToSave);

        return redirect()->route('liabilitas.index')
            ->with('success', 'Data Liabilitas Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        // Ensure the liabilitas belongs to the current user
        $liabilitas = Liabilitas::where('id_user', Auth::id())
            ->findOrFail($id);

        // Filter accounts by the current user's ID
        $dropdownList = Akun::where('id_user', Auth::id())
            ->get(['no_reff', 'nama_reff']);

        return view('form_liabilitas', [
            'title' => 'Edit Liabilitas',
            'data' => $liabilitas,
            'action' => route('liabilitas.update', $id),
            'dropdownList' => $dropdownList,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Ensure the liabilitas belongs to the current user
        $liabilitas = Liabilitas::where('id_user', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'nama_reff' => [
                'required', 
                'exists:akun,nama_reff,id_user,' . Auth::id() // Add user ID validation
            ],
            'tgl_liabilitas' => 'required|date',
            'jenis_saldo' => 'required|in:debit,kredit',
            'saldo' => 'required|numeric',
        ]);

        // Ambil no_reff berdasarkan nama_reff dan id_user
        $selectedAkun = Akun::where('nama_reff', $validated['nama_reff'])
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $liabilitas->update([
            'no_reff' => $selectedAkun->no_reff,
            'tgl_liabilitas' => $validated['tgl_liabilitas'],
            'jenis_saldo' => $validated['jenis_saldo'],
            'saldo' => $validated['saldo'],
            'id_user' => Auth::id(),
            'tgl_input' => now(),
        ]);

        return redirect()->route('liabilitas.index')
            ->with('success', 'Data Liabilitas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $liabilitas = Liabilitas::where('id_liabilitas', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $liabilitas->delete();
        return redirect()->route('liabilitas.index')
            ->with('success', 'Data Liabilitas berhasil dihapus');
    }

    // Metode AJAX untuk mendapatkan no_reff berdasarkan nama_reff
    public function getNoReff(Request $request)
    {
        $namaReff = $request->get('nama_reff');
        
        // Filter by both nama_reff and current user's ID
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
<?php

namespace App\Http\Controllers;

use App\Models\Pendapatan;
use Illuminate\Http\Request;
use App\Models\Akun;
use Illuminate\Support\Facades\Auth;

class PendapatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Filter pendapatan by current user's ID
        $listPendapatan = Pendapatan::where('id_user', Auth::id())->get();
        
        // Filter years based on current user's pendapatan
        $tahun = Pendapatan::where('id_user', Auth::id())
            ->selectRaw('YEAR(tgl_pendapatan) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get();

        return view('pendapatan_main', [
            'listPendapatan' => $listPendapatan,
            'tahun' => $tahun,
            'title' => 'Pendapatan'
        ]);
    }

    public function detail(Request $request)
    {
        $month = $request->input('bulan');
        $year = $request->input('tahun');

        if (empty($month) || empty($year)) {
            return redirect()->route('pendapatan.index');
        }

        // Filter pendapatan by current user's ID, month, and year
        $pendapatan = Pendapatan::with('akun')
            ->where('id_user', Auth::id())
            ->whereMonth('tgl_pendapatan', $month)
            ->whereYear('tgl_pendapatan', $year)
            ->get();

        if ($pendapatan->isEmpty()) {
            return redirect()->route('pendapatan.index')
                ->with('error', 'Data Pendapatan Dengan Bulan ' . $this->getMonthName($month) . 
                    ' Pada Tahun ' . $year . ' Tidak Ditemukan');
        }

        $totalDebit = $pendapatan->where('jenis_saldo', 'debit')->sum('saldo');
        $totalKredit = $pendapatan->where('jenis_saldo', 'kredit')->sum('saldo');

        return view('pendapatan', [
            'pendapatan' => $pendapatan,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'title' => 'Pendapatan'
        ]);
    }

    public function create()
    {
        // Filter accounts by the current user's ID
        $dropdownList = Akun::where('id_user', Auth::id())
            ->get(['no_reff', 'nama_reff']);

        return view('form_pendapatan', [
            'title' => 'Tambah Pendapatan',
            'data' => new Pendapatan(),
            'action' => route('pendapatan.store'),
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
            'tgl_pendapatan' => 'required|date',
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
            'tgl_pendapatan' => $validated['tgl_pendapatan'],
            'jenis_saldo' => $validated['jenis_saldo'],
            'saldo' => $validated['saldo'],
            'id_user' => Auth::id(),
            'tgl_input' => now(),
        ];

        // Simpan data ke tabel Pendapatan
        Pendapatan::create($dataToSave);

        return redirect()->route('pendapatan.index')
            ->with('success', 'Data Pendapatan Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        // Ensure the pendapatan belongs to the current user
        $pendapatan = Pendapatan::where('id_user', Auth::id())
            ->findOrFail($id);

        // Filter accounts by the current user's ID
        $dropdownList = Akun::where('id_user', Auth::id())
            ->get(['no_reff', 'nama_reff']);

        return view('form_pendapatan', [
            'title' => 'Edit Pendapatan',
            'data' => $pendapatan,
            'action' => route('pendapatan.update', $id),
            'dropdownList' => $dropdownList,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Ensure the pendapatan belongs to the current user
        $pendapatan = Pendapatan::where('id_user', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'nama_reff' => [
                'required', 
                'exists:akun,nama_reff,id_user,' . Auth::id() // Add user ID validation
            ],
            'tgl_pendapatan' => 'required|date',
            'jenis_saldo' => 'required|in:debit,kredit',
            'saldo' => 'required|numeric',
        ]);

        // Ambil no_reff berdasarkan nama_reff dan id_user
        $selectedAkun = Akun::where('nama_reff', $validated['nama_reff'])
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $pendapatan->update([
            'no_reff' => $selectedAkun->no_reff,
            'tgl_pendapatan' => $validated['tgl_pendapatan'],
            'jenis_saldo' => $validated['jenis_saldo'],
            'saldo' => $validated['saldo'],
            'id_user' => Auth::id(),
            'tgl_input' => now(),
        ]);

        return redirect()->route('pendapatan.index')
            ->with('success', 'Data Pendapatan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pendapatan = Pendapatan::where('id_pendapatan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $pendapatan->delete();
        return redirect()->route('pendapatan.index')
            ->with('success', 'Data Pendapatan berhasil dihapus');
    }

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
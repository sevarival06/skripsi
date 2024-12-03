<?php

namespace App\Http\Controllers;

use App\Models\Beban;
use Illuminate\Http\Request;
use App\Models\Akun;
use Illuminate\Support\Facades\Auth;

class BebanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $listBeban = Beban::where('id_user', auth()->user()->id_user)->get();
        $tahun = Beban::where('id_user', auth()->user()->id_user)
            ->selectRaw('YEAR(tgl_beban) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get();

        return view('beban_main', [
            'listBeban' => $listBeban,
            'tahun' => $tahun,
            'title' => 'Beban'
        ]);
    }

    public function detail(Request $request)
    {
        $month = $request->input('bulan');
        $year = $request->input('tahun');

        if (empty($month) || empty($year)) {
            return redirect()->route('beban.index');
        }

        $beban = Beban::with('akun')
            ->where('id_user', auth()->user()->id_user)
            ->whereMonth('tgl_beban', $month)
            ->whereYear('tgl_beban', $year)
            ->get();

        if ($beban->isEmpty()) {
            return redirect()->route('beban.index')
                ->with('error', 'Data Beban Dengan Bulan ' . $this->getMonthName($month) . 
                    ' Pada Tahun ' . $year . ' Tidak Ditemukan');
        }

        $totalDebit = $beban->where('jenis_saldo', 'debit')->sum('saldo');
        $totalKredit = $beban->where('jenis_saldo', 'kredit')->sum('saldo');

        return view('beban', [
            'beban' => $beban,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'title' => 'Beban'
        ]);
    }

    public function create()
    {
        // Ambil akun yang sesuai dengan user yang sedang login
        $dropdownList = Akun::where('id_user', auth()->user()->id_user)
            ->get(['no_reff', 'nama_reff']);

        return view('form_beban', [
            'title' => 'Tambah Beban',
            'data' => new Beban(),
            'action' => route('beban.store'),
            'dropdownList' => $dropdownList,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_reff' => 'required|exists:akun,nama_reff',
            'tgl_beban' => 'required|date',
            'jenis_saldo' => 'required|in:debit,kredit',
            'saldo' => 'required|numeric',
        ]);

        // Ambil no_reff berdasarkan nama_reff
        $selectedAkun = Akun::where('nama_reff', $validated['nama_reff'])->firstOrFail();

        // Buat array data untuk disimpan
        $dataToSave = [
            'no_reff' => $selectedAkun->no_reff,
            'tgl_beban' => $validated['tgl_beban'],
            'jenis_saldo' => $validated['jenis_saldo'],
            'saldo' => $validated['saldo'],
            'id_user' => auth()->user()->id_user, // Sesuaikan dengan kolom id_user
            'tgl_input' => now(), // Tambahkan tgl_input secara eksplisit
        ];

        // Simpan data ke tabel Beban
        Beban::create($dataToSave);

        return redirect()->route('beban.index')
            ->with('success', 'Data Beban Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $beban = Beban::findOrFail($id);
        $dropdownList = Akun::all(['no_reff', 'nama_reff']);

        return view('form_beban', [
            'title' => 'Edit Beban',
            'data' => $beban,
            'action' => route('beban.update', $id),
            'dropdownList' => $dropdownList,
        ]);
    }

    public function update(Request $request, $id)  // Ubah parameter menjadi $id
    {
        $beban = Beban::findOrFail($id);
        
        $validated = $request->validate([
            'nama_reff' => 'required|exists:akun,nama_reff',
            'tgl_beban' => 'required|date',
            'jenis_saldo' => 'required|in:debit,kredit',
            'saldo' => 'required|numeric',
        ]);

        $selectedAkun = Akun::where('nama_reff', $validated['nama_reff'])->firstOrFail();

        $beban->update([
            'no_reff' => $selectedAkun->no_reff,
            'tgl_beban' => $validated['tgl_beban'],
            'jenis_saldo' => $validated['jenis_saldo'],
            'saldo' => $validated['saldo'],
            'id_user' => auth()->id(),
            'tgl_input' => now(),
        ]);

        return redirect()->route('beban.index')
            ->with('success', 'Data Beban berhasil diperbarui');
    }

    public function destroy($id)
    {
        $beban = Beban::where('id_beban', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $beban->delete();
        return redirect()->route('beban.index')
            ->with('success', 'Data Beban berhasil dihapus');
    }

    // Metode AJAX untuk mendapatkan no_reff berdasarkan nama_reff
    public function getNoReff(Request $request)
    {
        $namaReff = $request->get('nama_reff');
        $akun = Akun::where('nama_reff', $namaReff)
            ->where('id_user', auth()->user()->id_user)
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

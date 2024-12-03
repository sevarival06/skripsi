<?php
namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;
use App\Models\Akun;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Ambil jurnal hanya untuk user yang sedang login
        $listJurnal = Jurnal::where('id_user', Auth::id())
            ->orderByRaw('YEAR(tgl_transaksi) DESC, MONTH(tgl_transaksi) DESC')
            ->get();

        $tahun = Jurnal::where('id_user', Auth::id())
            ->selectRaw('YEAR(tgl_transaksi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get();

        $bulanList = Jurnal::where('id_user', Auth::id())
            ->selectRaw('MONTH(tgl_transaksi) as bulan, YEAR(tgl_transaksi) as tahun')
            ->distinct()
            ->orderByRaw('tahun DESC, bulan DESC')
            ->get();

        $groupedJurnal = $listJurnal->groupBy(function($item) {
            return date('m-Y', strtotime($item->tgl_transaksi));
        });

        return view('jurnal_main', [
            'listJurnal' => $listJurnal,
            'tahun' => $tahun,
            'bulanList' => $bulanList,
            'groupedJurnal' => $groupedJurnal,
            'title' => 'Jurnal'
        ]);
    }

    public function detail(Request $request)
    {
        $month = $request->input('bulan');
        $year = $request->input('tahun');

        if (empty($month) || empty($year)) {
            return redirect()->route('jurnal.index');
        }

        $jurnal = Jurnal::with('akun')
            ->where('id_user', Auth::id())
            ->whereMonth('tgl_transaksi', $month)
            ->whereYear('tgl_transaksi', $year)
            ->get();

        if ($jurnal->isEmpty()) {
            return redirect()->route('jurnal.index')
                ->with('error', 'Data Aset Dengan Bulan ' . $this->getMonthName($month) . 
                    ' Pada Tahun ' . $year . ' Tidak Ditemukan');
        }

        $totalDebit = $jurnal->where('jenis_saldo', 'debit')->sum('saldo');
        $totalKredit = $jurnal->where('jenis_saldo', 'kredit')->sum('saldo');

        return view('jurnal', [
            'jurnal' => $jurnal,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'title' => 'Jurnal'
        ]);
    }

    public function create()
    {
        // Ambil daftar akun hanya untuk user yang sedang login
        $dropdownList = Akun::where('id_user', Auth::id())
            ->orderBy('nama_reff', 'asc')
            ->get(['no_reff', 'nama_reff']);

        return view('form_jurnal', [
            'title' => 'Tambah Jurnal',
            'data' => new Jurnal(),
            'action' => route('jurnal.store'),
            'dropdownList' => $dropdownList
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_reff' => [
                'required', 
                'exists:akun,nama_reff,id_user,'.Auth::id() // Tambahkan validasi user
            ],
            'tgl_transaksi' => 'required|date',
            'jenis_saldo' => 'required|in:debit,kredit',
            'saldo' => 'required|numeric',
        ]);

        // Ambil no_reff berdasarkan nama_reff milik user yang sedang login
        $selectedAkun = Akun::where('nama_reff', $validated['nama_reff'])
            ->where('id_user', Auth::id())
            ->firstOrFail();
        
        $dataToSave = [
            'no_reff' => $selectedAkun->no_reff,
            'tgl_transaksi' => $validated['tgl_transaksi'],
            'jenis_saldo' => $validated['jenis_saldo'],
            'saldo' => $validated['saldo'],
            'id_user' => Auth::id(),
            'tgl_input' => now()
        ];

        Jurnal::create($dataToSave);

        return redirect()->route('jurnal.index')
            ->with('success', 'Data Aset Berhasil Ditambahkan');
    }


    public function edit(Jurnal $jurnal)
    {
        // Pastikan hanya user yang memiliki Data Aset yang bisa mengedit
        if ($jurnal->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil daftar akun hanya untuk user yang sedang login
        $dropdownList = Akun::where('id_user', Auth::id())
            ->orderBy('nama_reff', 'asc')
            ->get(['no_reff', 'nama_reff']);

        return view('form_jurnal', [
            'title' => 'Edit Jurnal',
            'data' => $jurnal,
            'action' => route('jurnal.update', ['jurnal' => $jurnal->id_transaksi]),
            'dropdownList' => $dropdownList
        ]);
    }

    public function update(Request $request, Jurnal $jurnal)
    {
        // Pastikan hanya user yang memiliki Data Aset yang bisa mengupdate
        if ($jurnal->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nama_reff' => 'required|exists:akun,nama_reff',
            'tgl_transaksi' => 'required|date',
            'jenis_saldo' => 'required|in:debit,kredit',
            'saldo' => 'required|numeric'
        ]);

        $selectedAkun = Akun::where('nama_reff', $validated['nama_reff'])->firstOrFail();
    
        $jurnal->update([
            'no_reff' => $selectedAkun->no_reff,
            'tgl_transaksi' => $validated['tgl_transaksi'],
            'jenis_saldo' => $validated['jenis_saldo'],
            'saldo' => $validated['saldo'],
            'id_user' => Auth::id(),
            'tgl_input' => now()
        ]);

        return redirect()->route('jurnal.index')
            ->with('success', 'Data Aset berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jurnal = Jurnal::where('id_transaksi', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $jurnal->delete();
        return redirect()->route('jurnal.index')
            ->with('success', 'Data Aset berhasil dihapus');
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
                'no_reff' => $akun->no_reff
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data akun tidak ditemukan'
        ], 404);
    }

        private function getMonthName($month)
        {
        return \Carbon\Carbon::create()->month($month)->locale('id')->monthName;
        }

        function bulan($bulan) {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $namaBulan[$bulan] ?? 'Bulan Tidak Diketahui';
    }
        
}

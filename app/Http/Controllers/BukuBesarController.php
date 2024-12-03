<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Liabilitas;
use App\Models\Ekuitas;
use App\Models\Pendapatan;
use App\Models\Beban;
use App\Models\Akun;
use Carbon\Carbon;

class BukuBesarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $id_user = auth()->id(); // Mengambil ID user yang login

        // Jurnal
        $listJurnal = Jurnal::where('id_user', $id_user)
            ->orderByRaw('YEAR(tgl_transaksi) DESC, MONTH(tgl_transaksi) DESC')
            ->with('akun')
            ->get();

        // Liabilitas
        $listLiabilitas = Liabilitas::where('id_user', $id_user)
            ->orderByRaw('YEAR(tgl_liabilitas) DESC, MONTH(tgl_liabilitas) DESC')
            ->with('akun')
            ->get();

        // Ekuitas
        $listEkuitas = Ekuitas::where('id_user', $id_user)
            ->orderByRaw('YEAR(tgl_ekuitas) DESC, MONTH(tgl_ekuitas) DESC')
            ->with('akun')
            ->get();

        // Pendapatan
        $listPendapatan = Pendapatan::where('id_user', $id_user)
            ->orderByRaw('YEAR(tgl_pendapatan) DESC, MONTH(tgl_pendapatan) DESC')
            ->with('akun')
            ->get();

        // Beban
        $listBeban = Beban::where('id_user', $id_user)
            ->orderByRaw('YEAR(tgl_beban) DESC, MONTH(tgl_beban) DESC')
            ->with('akun')
            ->get();

        // Mengambil tahun unik dari semua transaksi
        $tahunJurnal = Jurnal::where('id_user', $id_user)
            ->selectRaw('YEAR(tgl_transaksi) as tahun')
            ->distinct();
        
        $tahunLiabilitas = Liabilitas::where('id_user', $id_user)
            ->selectRaw('YEAR(tgl_liabilitas) as tahun')
            ->distinct();
        
        $tahunEkuitas = Ekuitas::where('id_user', $id_user)
            ->selectRaw('YEAR(tgl_ekuitas) as tahun')
            ->distinct();
        
        $tahunPendapatan = Pendapatan::where('id_user', $id_user)
            ->selectRaw('YEAR(tgl_pendapatan) as tahun')
            ->distinct();
        
        $tahunBeban = Beban::where('id_user', $id_user)
            ->selectRaw('YEAR(tgl_beban) as tahun')
            ->distinct();

        // Menggabungkan dan mengurutkan tahun
        $tahun = collect([])
            ->concat($tahunJurnal->pluck('tahun'))
            ->concat($tahunLiabilitas->pluck('tahun'))
            ->concat($tahunEkuitas->pluck('tahun'))
            ->concat($tahunPendapatan->pluck('tahun'))
            ->concat($tahunBeban->pluck('tahun'))
            ->unique()
            ->sort()
            ->values();

        // Array bulan manual
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Mengelompokkan Jurnal
        $groupedJurnal = $listJurnal->groupBy(function ($item) {
            return date('n-Y', strtotime($item->tgl_transaksi));
        })->map(function ($items, $key) {
            return [
                'bulan_tahun' => $key,
                'total' => $items->sum('nilai'),
                'data' => $items,
            ];
        });

        // Mengelompokkan Liabilitas
        $groupedLiabilitas = $listLiabilitas->groupBy(function ($item) {
            return date('n-Y', strtotime($item->tgl_liabilitas));
        })->map(function ($items, $key) {
            return [
                'bulan_tahun' => $key,
                'total' => $items->sum('nilai'),
                'data' => $items,
            ];
        });

        // Mengelompokkan Ekuitas
        $groupedEkuitas = $listEkuitas->groupBy(function ($item) {
            return date('n-Y', strtotime($item->tgl_ekuitas));
        })->map(function ($items, $key) {
            return [
                'bulan_tahun' => $key,
                'total' => $items->sum('nilai'),
                'data' => $items,
            ];
        });

        // Mengelompokkan Pendapatan
        $groupedPendapatan = $listPendapatan->groupBy(function ($item) {
            return date('n-Y', strtotime($item->tgl_pendapatan));
        })->map(function ($items, $key) {
            return [
                'bulan_tahun' => $key,
                'total' => $items->sum('nilai'),
                'data' => $items,
            ];
        });

        // Mengelompokkan Beban
        $groupedBeban = $listBeban->groupBy(function ($item) {
            return date('n-Y', strtotime($item->tgl_beban));
        })->map(function ($items, $key) {
            return [
                'bulan_tahun' => $key,
                'total' => $items->sum('nilai'),
                'data' => $items,
            ];
        });

        return view('buku_besar_main', [
            'listJurnal' => $listJurnal,
            'listLiabilitas' => $listLiabilitas,
            'listEkuitas' => $listEkuitas,
            'listPendapatan' => $listPendapatan,
            'listBeban' => $listBeban,
            'tahun' => $tahun,
            'bulanList' => $bulanList,
            'groupedJurnal' => $groupedJurnal,
            'groupedLiabilitas' => $groupedLiabilitas,
            'groupedEkuitas' => $groupedEkuitas,
            'groupedPendapatan' => $groupedPendapatan,
            'groupedBeban' => $groupedBeban,
            'title' => 'Buku Besar'
        ]);
    }

    // Detail Buku Besar (buku_besar.blade.php)
    public function detail(Request $request)
    {
        $userId = auth()->user()->id;
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if (empty($bulan) || empty($tahun)) {
            return redirect()->route('buku.besar')->with('error', 'Bulan dan tahun harus diisi');
        }

        // Get all entries for the selected month and year
        $data = Jurnal::where('id_user', $userId)
                      ->whereMonth('tgl_transaksi', $bulan)
                      ->whereYear('tgl_transaksi', $tahun)
                      ->orderBy('tgl_transaksi')
                      ->get();

        // Calculate running balance
        $runningBalance = 0;
        foreach ($data as $item) {
            if ($item->jenis_saldo == 'debit') {
                $runningBalance += $item->saldo;
            } else {
                $runningBalance -= $item->saldo;
            }
            $item->running_balance = $runningBalance;
        }

        // Get data for other categories
        $data = $this->getDataBukuBesar($bulan, $tahun, $userId);
        $liabilitas = $this->getLiabilitas($bulan, $tahun, $userId);
        $ekuitas = $this->getEkuitas($bulan, $tahun, $userId);
        $pendapatan = $this->getPendapatan($bulan, $tahun, $userId);
        $beban = $this->getBeban($bulan, $tahun, $userId);

        return view('buku_besar', compact(
            'data', 'liabilitas', 'ekuitas',
            'pendapatan', 'beban'
        ));
    }

    // Controller
    public function showBukuBesar(Request $request)
{
    $id_user = auth()->id(); // Mengambil ID user yang sedang login

    $tahun = Jurnal::where('id_user', $id_user)
        ->selectRaw('YEAR(tgl_transaksi) as tahun')
        ->groupBy('tahun')
        ->get()
        ->pluck('tahun');

    // Mengambil data jurnal dengan relasi ke tabel akun dan filter by user
    $listJurnal = Jurnal::with('akun')
                    ->where('id_user', $id_user)
                    ->get();

    // Kelompokkan berdasarkan bulan dan tahun
    $groupedJurnal = $listJurnal->groupBy(function($item) {
        return date('n-Y', strtotime($item->tgl_transaksi));
    });

    $bulanList = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    return view('buku_besar_main', compact('tahun', 'groupedJurnal', 'bulanList'));
}

private function getDataBukuBesar($bulan, $tahun)
{
    $id_user = auth()->id();
    // Ambil data jurnal dengan relasi akun dan filter by user
    return Jurnal::with('akun')
                ->where('id_user', $id_user)
                ->whereMonth('tgl_transaksi', $bulan)
                ->whereYear('tgl_transaksi', $tahun)
                ->get();
}

private function getLiabilitas($bulan, $tahun)
{
    $id_user = auth()->id();
    // Ambil data liabilitas dengan relasi akun dan filter by user
    return Liabilitas::with('akun')
                    ->where('id_user', $id_user)
                    ->whereMonth('tgl_liabilitas', $bulan)
                    ->whereYear('tgl_liabilitas', $tahun)
                    ->get();
}

private function getEkuitas($bulan, $tahun)
{
    $id_user = auth()->id();
    // Ambil data ekuitas dengan relasi akun dan filter by user
    return Ekuitas::with('akun')
                ->where('id_user', $id_user)
                ->whereMonth('tgl_ekuitas', $bulan)
                ->whereYear('tgl_ekuitas', $tahun)
                ->get();
}

private function getPendapatan($bulan, $tahun)
{
    $id_user = auth()->id();
    // Ambil data pendapatan dengan relasi akun dan filter by user
    return Pendapatan::with('akun')
                    ->where('id_user', $id_user)
                    ->whereMonth('tgl_pendapatan', $bulan)
                    ->whereYear('tgl_pendapatan', $tahun)
                    ->get();
}

private function getBeban($bulan, $tahun)
{
    $id_user = auth()->id();
    // Ambil data beban dengan relasi akun dan filter by user
    return Beban::with('akun')
                ->where('id_user', $id_user)
                ->whereMonth('tgl_beban', $bulan)
                ->whereYear('tgl_beban', $tahun)
                ->get();
}
}
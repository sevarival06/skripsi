<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Liabilitas;
use App\Models\Ekuitas;
use App\Models\Akun;
use Carbon\Carbon;

class LaporanPosisikeuanganController extends Controller
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
        
        // Menggabungkan dan mengurutkan tahun
        $tahun = collect([])
            ->concat($tahunJurnal->pluck('tahun'))
            ->concat($tahunLiabilitas->pluck('tahun'))
            ->concat($tahunEkuitas->pluck('tahun'))
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

        return view('posisikeuangan_main', [
            'listJurnal' => $listJurnal,
            'listLiabilitas' => $listLiabilitas,
            'listEkuitas' => $listEkuitas,
            'tahun' => $tahun,
            'bulanList' => $bulanList,
            'groupedJurnal' => $groupedJurnal,
            'groupedLiabilitas' => $groupedLiabilitas,
            'groupedEkuitas' => $groupedEkuitas,
            'title' => 'Laporan Posisi Keuangan'
        ]);
    }

    // Detail Laporan Posisi Keuangan (posisikeuangan.blade.php)
    public function detail(Request $request)
    {
        $userId = auth()->user()->id;
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if (empty($bulan) || empty($tahun)) {
            return redirect()->route('posisikeuangan')->with('error', 'Bulan dan tahun harus diisi');
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
        $data = $this->getDataPosisikeuangan($bulan, $tahun, $userId);
        $liabilitas = $this->getLiabilitas($bulan, $tahun, $userId);
        $ekuitas = $this->getEkuitas($bulan, $tahun, $userId);

        return view('posisikeuangan', compact(
            'data', 'liabilitas', 'ekuitas'
        ));
    }

    // Controller
    public function showPosisikeuangan(Request $request)
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

        return view('posisikeuangan_main', compact('tahun', 'groupedJurnal', 'bulanList'));
    }

    private function getDataPosisikeuangan($bulan, $tahun)
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

}
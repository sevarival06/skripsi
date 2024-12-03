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

class LaporanLabaRugiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $id_user = auth()->id(); // Mengambil ID user yang login

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
        $tahunPendapatan = Pendapatan::where('id_user', $id_user)
            ->selectRaw('YEAR(tgl_pendapatan) as tahun')
            ->distinct();
        
        $tahunBeban = Beban::where('id_user', $id_user)
            ->selectRaw('YEAR(tgl_beban) as tahun')
            ->distinct();

        // Menggabungkan dan mengurutkan tahun
        $tahun = collect([])
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

        return view('labarugi_main', [
            'listPendapatan' => $listPendapatan,
            'listBeban' => $listBeban,
            'tahun' => $tahun,
            'bulanList' => $bulanList,
            'groupedPendapatan' => $groupedPendapatan,
            'groupedBeban' => $groupedBeban,
            'title' => 'Laporan Labarugi'
        ]);
    }

    // Detail Laporan Labarugi (labarugi.blade.php)
    public function detail(Request $request)
    {
        $userId = auth()->user()->id;
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if (empty($bulan) || empty($tahun)) {
            return redirect()->route('labarugi')->with('error', 'Bulan dan tahun harus diisi');
        }

        // Get data for other categories
        $pendapatan = $this->getPendapatan($bulan, $tahun, $userId);
        $beban = $this->getBeban($bulan, $tahun, $userId);

        // Calculate total debit and total kredit for pendapatan and beban
        $total_debit = 0;
        $total_kredit = 0;

        // Assuming both pendapatan and beban contain 'jenis_saldo' and 'saldo' fields
        foreach ($pendapatan as $item) {
            if ($item->jenis_saldo == 'debit') {
                $total_debit += $item->saldo;
            } elseif ($item->jenis_saldo == 'kredit') {
                $total_kredit += $item->saldo;
            }
        }

        foreach ($beban as $item) {
            if ($item->jenis_saldo == 'debit') {
                $total_debit += $item->saldo;
            } elseif ($item->jenis_saldo == 'kredit') {
                $total_kredit += $item->saldo;
            }
        }

        return view('labarugi', compact(
            'pendapatan', 'beban', 'total_debit', 'total_kredit'
        ));
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
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendapatan;
use App\Models\Beban;
use PDF;

class Laporan1Controller extends Controller
{
    /**
     * Menampilkan halaman laporan laba rugi.
     */
    public function index(Request $request)
    {
        // Ambil data bulan dan tahun dari request atau defaultkan ke bulan dan tahun terakhir
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // Ambil data pendapatan berdasarkan bulan dan tahun dengan field tgl_pendapatan
        $pendapatan = Pendapatan::with('akun')
            ->whereMonth('tgl_pendapatan', $bulan)
            ->whereYear('tgl_pendapatan', $tahun)
            ->get();

        // Ambil data beban berdasarkan bulan dan tahun dengan field tgl_beban
        $beban = Beban::with('akun')
            ->whereMonth('tgl_beban', $bulan)
            ->whereYear('tgl_beban', $tahun)
            ->get();

        // Menghitung total pendapatan dan beban
        $totalPendapatanDebit = $pendapatan->where('jenis_saldo', 'debit')->sum('saldo');
        $totalPendapatanKredit = $pendapatan->where('jenis_saldo', 'kredit')->sum('saldo');

        $totalBebanDebit = $beban->where('jenis_saldo', 'debit')->sum('saldo');
        $totalBebanKredit = $beban->where('jenis_saldo', 'kredit')->sum('saldo');

        // Menghitung Laba/Rugi
        $totalPendapatan = $totalPendapatanKredit - $totalPendapatanDebit;
        $totalBeban = $totalBebanDebit - $totalBebanKredit;
        $totalSaldo = $totalPendapatan - $totalBeban;

        // Daftar bulan dalam bahasa Indonesia
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

        // Kirimkan data ke view
        return view('labarugi', compact(
            'pendapatan',
            'beban',
            'totalPendapatanDebit',
            'totalPendapatanKredit',
            'totalBebanDebit',
            'totalBebanKredit',
            'totalSaldo',
            'bulan',
            'tahun',
            'bulanList'
        ));
    }

    /**
     * Mengunduh laporan laba rugi dalam format PDF.
     */
    public function unduhPDF(Request $request)
    {
        // Ambil data bulan dan tahun dari request, jika tidak ada default ke bulan dan tahun sekarang
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        // Ambil data pendapatan berdasarkan bulan dan tahun dengan field tgl_pendapatan
        $pendapatan = Pendapatan::with('akun')
            ->whereMonth('tgl_pendapatan', $bulan)
            ->whereYear('tgl_pendapatan', $tahun)
            ->get();

        // Ambil data beban berdasarkan bulan dan tahun dengan field tgl_beban
        $beban = Beban::with('akun')
            ->whereMonth('tgl_beban', $bulan)
            ->whereYear('tgl_beban', $tahun)
            ->get();

        // Menghitung total pendapatan dan beban
        $totalPendapatanDebit = $pendapatan->where('jenis_saldo', 'debit')->sum('saldo');
        $totalPendapatanKredit = $pendapatan->where('jenis_saldo', 'kredit')->sum('saldo');

        $totalBebanDebit = $beban->where('jenis_saldo', 'debit')->sum('saldo');
        $totalBebanKredit = $beban->where('jenis_saldo', 'kredit')->sum('saldo');

        // Menghitung Laba/Rugi
        $totalPendapatan = $totalPendapatanKredit - $totalPendapatanDebit;
        $totalBeban = $totalBebanDebit - $totalBebanKredit;
        $totalSaldo = $totalPendapatan - $totalBeban;

        // Daftar bulan dalam bahasa Indonesia
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

        // Generate PDF dari view
        $pdf = PDF::loadView('labarugi_pdf', compact(
            'pendapatan',
            'beban',
            'totalPendapatanDebit',
            'totalPendapatanKredit',
            'totalBebanDebit',
            'totalBebanKredit',
            'totalSaldo',
            'bulan',
            'tahun',
            'bulanList'
        ))->setPaper('a4', 'portrait');

        // Mengunduh PDF dengan nama file yang sesuai bulan dan tahun yang dipilih
        return $pdf->download("Laporan_Laba_Rugi_{$bulanList[$bulan]}_{$tahun}.pdf");
    }
}

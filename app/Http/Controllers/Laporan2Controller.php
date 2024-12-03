<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal; // Model untuk transaksi (jurnal)
use App\Models\Liabilitas; // Model untuk liabilitas
use App\Models\Ekuitas; // Model untuk ekuitas
use PDF;

class Laporan2Controller extends Controller
{
    /**
     * Menampilkan halaman laporan posisi keuangan.
     */
    public function index(Request $request)
    {
        $id_user = auth()->id(); // ID pengguna yang login

        // Ambil parameter bulan dan tahun dari request atau gunakan bulan dan tahun sekarang sebagai default
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        // Ambil data jurnal (dari tabel transaksi)
        $data = Transaksi::with('akun')
            ->where('id_user', $id_user)
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->get();

        // Ambil data liabilitas
        $liabilitas = Liabilitas::with('akun')
            ->where('id_user', $id_user)
            ->whereMonth('tgl_liabilitas', $bulan)
            ->whereYear('tgl_liabilitas', $tahun)
            ->get();

        // Ambil data ekuitas
        $ekuitas = Ekuitas::with('akun')
            ->where('id_user', $id_user)
            ->whereMonth('tgl_ekuitas', $bulan)
            ->whereYear('tgl_ekuitas', $tahun)
            ->get();

        // Daftar bulan dalam bahasa Indonesia
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('posisikeuangan_main', compact('data', 'liabilitas', 'ekuitas', 'bulan', 'tahun', 'bulanList'));
    }

    /**
     * Mengunduh laporan posisi keuangan dalam format PDF.
     */
    public function unduhPDF(Request $request)
    {
        $id_user = auth()->id();

        // Ambil parameter bulan dan tahun dari request atau gunakan bulan dan tahun sekarang sebagai default
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        // Ambil data jurnal (dari tabel transaksi)
        $data = Jurnal::with('akun')
            ->where('id_user', $id_user)
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->get();

        // Ambil data liabilitas
        $liabilitas = Liabilitas::with('akun')
            ->where('id_user', $id_user)
            ->whereMonth('tgl_liabilitas', $bulan)
            ->whereYear('tgl_liabilitas', $tahun)
            ->get();

        // Ambil data ekuitas
        $ekuitas = Ekuitas::with('akun')
            ->where('id_user', $id_user)
            ->whereMonth('tgl_ekuitas', $bulan)
            ->whereYear('tgl_ekuitas', $tahun)
            ->get();

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $pdf = PDF::loadView('posisikeuangan_pdf', compact('data', 'liabilitas', 'ekuitas', 'bulan', 'tahun', 'bulanList'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('posisi_keuangan.pdf');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    
    protected $fillable = [
        'id_user',
        'no_reff',
        'tgl_transaksi',
        'jenis_saldo',
        'saldo',
        'tgl_input'
    ];

    // Tambahkan boot method untuk mengisi tgl_input otomatis
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->tgl_input)) {
                $model->tgl_input = now();
            }
        });
    }
    
    // Relasi dengan Akun
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'no_reff', 'no_reff');
    }

    // Jika Anda ingin Laravel otomatis mengisi timestamp, aktifkan
    public $timestamps = true; // Set to false jika tidak ada kolom created_at dan updated_at

    public function getJurnalById($id)
    {
        $userId = auth()->user()->id;
        return Jurnal::where('id_transaksi', $id)
            ->where('id_user', $userId)
            ->first();
    }

    public function getJurnalByUserId()
    {
        $userId = auth()->user()->id;
        return Jurnal::where('id_user', $userId)->get();
    }

    public function countJurnalNoReff($noReff)
    {
        $userId = auth()->user()->id;
        return Jurnal::where('no_reff', $noReff)
            ->where('id_user', $userId)
            ->count();
    }

    public function getJurnalByYear()
    {
        $userId = auth()->user()->id;
        return Jurnal::select(DB::raw('year(tgl_transaksi) as year'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('year(tgl_transaksi)'))
            ->get();
    }

    public function getJurnalByYearAndMonth()
    {
        $userId = auth()->user()->id;
        return Jurnal::select(DB::raw('month(tgl_transaksi) as month, year(tgl_transaksi) as year'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('month(tgl_transaksi)'), DB::raw('year(tgl_transaksi)'))
            ->get();
    }

    public function getAkunInJurnal()
    {
        $userId = auth()->user()->id;
        return DB::table('transaksi')
            ->select('transaksi.no_reff', 'akun.no_reff', 'akun.nama_reff')
            ->join('akun', 'transaksi.no_reff', '=', 'akun.no_reff')
            ->where('transaksi.id_user', $userId)
            ->groupBy('akun.nama_reff')
            ->orderBy('akun.no_reff', 'ASC')
            ->get();
    }

    public function countAkunInJurnal()
    {
        $userId = auth()->user()->id;
        return DB::table('transaksi')
            ->select('transaksi.no_reff', 'akun.no_reff', 'akun.nama_reff')
            ->join('akun', 'transaksi.no_reff', '=', 'akun.no_reff')
            ->where('transaksi.id_user', $userId)
            ->groupBy('akun.nama_reff')
            ->orderBy('akun.no_reff', 'ASC')
            ->count();
    }

    public function getJurnalByNoReff($noReff)
    {
        $userId = auth()->user()->id;
        return DB::table('transaksi')
            ->select('transaksi.id_transaksi', 'transaksi.tgl_transaksi', 'akun.nama_reff', 'transaksi.no_reff', 'transaksi.jenis_saldo', 'transaksi.saldo', 'transaksi.tgl_input')
            ->join('akun', 'transaksi.no_reff', '=', 'akun.no_reff')
            ->where('transaksi.no_reff', $noReff)
            ->where('transaksi.id_user', $userId)
            ->orderBy('transaksi.tgl_transaksi', 'ASC')
            ->get();
    }

    public function getJurnalByNoReffMonthYear($noReff, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('transaksi')
            ->select('transaksi.id_transaksi', 'transaksi.tgl_transaksi', 'akun.nama_reff', 'transaksi.no_reff', 'transaksi.jenis_saldo', 'transaksi.saldo', 'transaksi.tgl_input')
            ->join('akun', 'transaksi.no_reff', '=', 'akun.no_reff')
            ->where('transaksi.no_reff', $noReff)
            ->whereMonth('transaksi.tgl_transaksi', $bulan)
            ->whereYear('transaksi.tgl_transaksi', $tahun)
            ->where('transaksi.id_user', $userId)
            ->orderBy('transaksi.tgl_transaksi', 'ASC')
            ->get();
    }

    public function getJurnalBydNoReffMonthYear($noReff, $bulan, $tahun, $jenisSaldo)
    {
        $userId = auth()->user()->id;
        return DB::table('transaksi')
            ->select('transaksi.id_transaksi', 'transaksi.tgl_transaksi', 'akun.nama_reff', 'transaksi.no_reff', 'transaksi.jenis_saldo', 'transaksi.saldo', 'transaksi.tgl_input')
            ->join('akun', 'transaksi.no_reff', '=', 'akun.no_reff')
            ->where('transaksi.no_reff', $noReff)
            ->whereMonth('transaksi.tgl_transaksi', $bulan)
            ->whereYear('transaksi.tgl_transaksi', $tahun)
            ->where('transaksi.jenis_saldo', $jenisSaldo)
            ->where('transaksi.id_user', $userId)
            ->orderBy('transaksi.tgl_transaksi', 'ASC')
            ->get();
    }

    public function getJurnalByNoReffSaldo($noReff)
    {
        $userId = auth()->user()->id;
        return DB::table('transaksi')
            ->select('transaksi.jenis_saldo', 'transaksi.saldo')
            ->join('akun', 'transaksi.no_reff', '=', 'akun.no_reff')
            ->where('transaksi.no_reff', $noReff)
            ->where('transaksi.id_user', $userId)
            ->orderBy('transaksi.tgl_transaksi', 'ASC')
            ->get();
    }

    public function getJurnalByNoReffSaldoMonthYear($noReff, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('transaksi')
            ->select('transaksi.jenis_saldo', 'transaksi.saldo')
            ->join('akun', 'transaksi.no_reff', '=', 'akun.no_reff')
            ->where('transaksi.no_reff', $noReff)
            ->whereMonth('transaksi.tgl_transaksi', $bulan)
            ->whereYear('transaksi.tgl_transaksi', $tahun)
            ->where('transaksi.id_user', $userId)
            ->orderBy('transaksi.tgl_transaksi', 'ASC')
            ->get();
    }

    public function getJurnalJoinAkun()
    {
        $userId = auth()->user()->id;
        return DB::table('transaksi')
            ->select('transaksi.id_transaksi', 'transaksi.tgl_transaksi', 'akun.nama_reff', 'transaksi.no_reff', 'transaksi.jenis_saldo', 'transaksi.saldo', 'transaksi.tgl_input')
            ->join('akun', 'transaksi.no_reff', '=', 'akun.no_reff')
            ->where('transaksi.id_user', $userId)
            ->orderBy('transaksi.tgl_transaksi', 'ASC')
            ->orderBy('transaksi.tgl_input', 'ASC')
            ->orderBy('transaksi.jenis_saldo', 'ASC')
            ->get();
    }

    public function getJurnalJoinAkunDetail($bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('transaksi')
            ->select('transaksi.id_transaksi', 'transaksi.tgl_transaksi', 'akun.nama_reff', 'transaksi.no_reff', 'transaksi.jenis_saldo', 'transaksi.saldo', 'transaksi.tgl_input')
            ->join('akun', 'transaksi.no_reff', '=', 'akun.no_reff')
            ->whereMonth('transaksi.tgl_transaksi', $bulan)
            ->whereYear('transaksi.tgl_transaksi', $tahun)
            ->where('transaksi.id_user', $userId)
            ->orderBy('transaksi.tgl_transaksi', 'ASC')
            ->orderBy('transaksi.tgl_input', 'ASC')
            ->orderBy('transaksi.jenis_saldo', 'ASC')
            ->get();
    }

    public function getTotalSaldoDetail($jenisSaldo, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return Jurnal::selectRaw('SUM(saldo) as total_saldo')
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->where('jenis_saldo', $jenisSaldo)
            ->where('id_user', $userId)
            ->first();
    }

    public function getTotalSaldo($jenisSaldo)
    {
        $userId = auth()->user()->id;
        return Jurnal::selectRaw('SUM(saldo) as total_saldo')
            ->where('jenis_saldo', $jenisSaldo)
            ->where('id_user', $userId)
            ->first();
    }

}


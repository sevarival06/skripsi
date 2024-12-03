<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pendapatan extends Model
{
    use HasFactory;

    protected $table = 'pendapatan';
    protected $primaryKey = 'id_pendapatan';
    
    protected $fillable = [
        'id_user',
        'no_reff',
        'tgl_pendapatan',
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

    public function getPendapatanById($id)
    {
        $userId = auth()->user()->id;
        return Pendapatan::where('id_pendapatan', $id)
            ->where('id_user', $userId)
            ->first();
    }

    public function getPendapatanByUserId()
    {
        $userId = auth()->user()->id;
        return Pendapatan::where('id_user', $userId)->get();
    }

    public function countPendapatanNoReff($noReff)
    {
        $userId = auth()->user()->id;
        return Pendapatan::where('no_reff', $noReff)
            ->where('id_user', $userId)
            ->count();
    }

    public function getPendapatanByYear()
    {
        $userId = auth()->user()->id;
        return Pendapatan::select(DB::raw('year(tgl_pendapatan) as year'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('year(tgl_pendapatan)'))
            ->get();
    }

    public function getPendapatanByYearAndMonth()
    {
        $userId = auth()->user()->id;
        return Pendapatan::select(DB::raw('month(tgl_pendapatan) as month, year(tgl_pendapatan) as year'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('month(tgl_pendapatan)'), DB::raw('year(tgl_pendapatan)'))
            ->get();
    }

    public function getAkunInPendapatan()
    {
        $userId = auth()->user()->id;
        return DB::table('pendapatan')
            ->select('pendapatan.no_reff', 'akun.no_reff', 'akun.nama_reff')
            ->join('akun', 'pendapatan.no_reff', '=', 'akun.no_reff')
            ->where('pendapatan.id_user', $userId)
            ->groupBy('akun.nama_reff')
            ->orderBy('akun.no_reff', 'ASC')
            ->get();
    }

    public function countAkunInPendapatan()
    {
        $userId = auth()->user()->id;
        return DB::table('pendapatan')
            ->select('pendapatan.no_reff', 'akun.no_reff', 'akun.nama_reff')
            ->join('akun', 'pendapatan.no_reff', '=', 'akun.no_reff')
            ->where('pendapatan.id_user', $userId)
            ->groupBy('akun.nama_reff')
            ->orderBy('akun.no_reff', 'ASC')
            ->count();
    }

    public function getPendapatanByNoReff($noReff)
    {
        $userId = auth()->user()->id;
        return DB::table('pendapatan')
            ->select('pendapatan.id_pendapatan', 'pendapatan.tgl_pendapatan', 'akun.nama_reff', 'pendapatan.no_reff', 'pendapatan.jenis_saldo', 'pendapatan.saldo', 'pendapatan.tgl_input')
            ->join('akun', 'pendapatan.no_reff', '=', 'akun.no_reff')
            ->where('pendapatan.no_reff', $noReff)
            ->where('pendapatan.id_user', $userId)
            ->orderBy('pendapatan.tgl_pendapatan', 'ASC')
            ->get();
    }

    public function getPendapatanByNoReffMonthYear($noReff, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('pendapatan')
            ->select('pendapatan.id_pendapatan', 'pendapatan.tgl_pendapatan', 'akun.nama_reff', 'pendapatan.no_reff', 'pendapatan.jenis_saldo', 'pendapatan.saldo', 'pendapatan.tgl_input')
            ->join('akun', 'pendapatan.no_reff', '=', 'akun.no_reff')
            ->where('pendapatan.no_reff', $noReff)
            ->whereMonth('pendapatan.tgl_pendapatan', $bulan)
            ->whereYear('pendapatan.tgl_pendapatan', $tahun)
            ->where('pendapatan.id_user', $userId)
            ->orderBy('pendapatan.tgl_pendapatan', 'ASC')
            ->get();
    }

    public function getPendapatanBydNoReffMonthYear($noReff, $bulan, $tahun, $jenisSaldo)
    {
        $userId = auth()->user()->id;
        return DB::table('pendapatan')
            ->select('pendapatan.id_pendapatan', 'pendapatan.tgl_pendapatan', 'akun.nama_reff', 'pendapatan.no_reff', 'pendapatan.jenis_saldo', 'pendapatan.saldo', 'pendapatan.tgl_input')
            ->join('akun', 'pendapatan.no_reff', '=', 'akun.no_reff')
            ->where('pendapatan.no_reff', $noReff)
            ->whereMonth('pendapatan.tgl_pendapatan', $bulan)
            ->whereYear('pendapatan.tgl_pendapatan', $tahun)
            ->where('pendapatan.jenis_saldo', $jenisSaldo)
            ->where('pendapatan.id_user', $userId)
            ->orderBy('pendapatan.tgl_pendapatan', 'ASC')
            ->get();
    }

    public function getPendapatanByNoReffSaldo($noReff)
    {
        $userId = auth()->user()->id;
        return DB::table('pendapatan')
            ->select('pendapatan.jenis_saldo', 'pendapatan.saldo')
            ->join('akun', 'pendapatan.no_reff', '=', 'akun.no_reff')
            ->where('pendapatan.no_reff', $noReff)
            ->where('pendapatan.id_user', $userId)
            ->orderBy('pendapatan.tgl_pendapatan', 'ASC')
            ->get();
    }

    public function getPendapatanByNoReffSaldoMonthYear($noReff, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('pendapatan')
            ->select('pendapatan.jenis_saldo', 'pendapatan.saldo')
            ->join('akun', 'pendapatan.no_reff', '=', 'akun.no_reff')
            ->where('pendapatan.no_reff', $noReff)
            ->whereMonth('pendapatan.tgl_pendapatan', $bulan)
            ->whereYear('pendapatan.tgl_pendapatan', $tahun)
            ->where('pendapatan.id_user', $userId)
            ->orderBy('pendapatan.tgl_pendapatan', 'ASC')
            ->get();
    }

    public function getPendapatanJoinAkun()
    {
        $userId = auth()->user()->id;
        return DB::table('pendapatan')
            ->select('pendapatan.id_pendapatan', 'pendapatan.tgl_pendapatan', 'akun.nama_reff', 'pendapatan.no_reff', 'pendapatan.jenis_saldo', 'pendapatan.saldo', 'pendapatan.tgl_input')
            ->join('akun', 'pendapatan.no_reff', '=', 'akun.no_reff')
            ->where('pendapatan.id_user', $userId)
            ->orderBy('pendapatan.tgl_pendapatan', 'ASC')
            ->orderBy('pendapatan.tgl_input', 'ASC')
            ->orderBy('pendapatan.jenis_saldo', 'ASC')
            ->get();
    }

    public function getPendapatanJoinAkunDetail($bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('pendapatan')
            ->select('pendapatan.id_pendapatan', 'pendapatan.tgl_pendapatan', 'akun.nama_reff', 'pendapatan.no_reff', 'pendapatan.jenis_saldo', 'pendapatan.saldo', 'pendapatan.tgl_input')
            ->join('akun', 'pendapatan.no_reff', '=', 'akun.no_reff')
            ->whereMonth('pendapatan.tgl_pendapatan', $bulan)
            ->whereYear('pendapatan.tgl_pendapatan', $tahun)
            ->where('pendapatan.id_user', $userId)
            ->orderBy('pendapatan.tgl_pendapatan', 'ASC')
            ->orderBy('pendapatan.tgl_input', 'ASC')
            ->orderBy('pendapatan.jenis_saldo', 'ASC')
            ->get();
    }

    public function getTotalSaldoDetail($jenisSaldo, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return Pendapatan::selectRaw('SUM(saldo) as total_saldo')
            ->whereMonth('tgl_pendapatan', $bulan)
            ->whereYear('tgl_pendapatan', $tahun)
            ->where('jenis_saldo', $jenisSaldo)
            ->where('id_user', $userId)
            ->first();
    }

    public function getTotalSaldo($jenisSaldo)
    {
        $userId = auth()->user()->id;
        return Pendapatan::selectRaw('SUM(saldo) as total_saldo')
            ->where('jenis_saldo', $jenisSaldo)
            ->where('id_user', $userId)
            ->first();
    }

}

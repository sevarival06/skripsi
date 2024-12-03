<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Liabilitas extends Model
{
    use HasFactory;

    protected $table = 'liabilitas';
    protected $primaryKey = 'id_liabilitas';
    
    protected $fillable = [
        'id_user',
        'no_reff',
        'tgl_liabilitas',
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

    public function getLiabilitasById($id)
    {
        $userId = auth()->user()->id;
        return Liabilitas::where('id_liabilitas', $id)
            ->where('id_user', $userId)
            ->first();
    }

    public function getLiabilitasByUserId()
    {
        $userId = auth()->user()->id;
        return Liabilitas::where('id_user', $userId)->get();
    }

    public function countLiabilitasNoReff($noReff)
    {
        $userId = auth()->user()->id;
        return Liabilitas::where('no_reff', $noReff)
            ->where('id_user', $userId)
            ->count();
    }

    public function getLiabilitasByYear()
    {
        $userId = auth()->user()->id;
        return Liabilitas::select(DB::raw('year(tgl_liabilitas) as year'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('year(tgl_liabilitas)'))
            ->get();
    }

    public function getLiabilitasByYearAndMonth()
    {
        $userId = auth()->user()->id;
        return Liabilitas::select(DB::raw('month(tgl_liabilitas) as month, year(tgl_liabilitas) as year'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('month(tgl_liabilitas)'), DB::raw('year(tgl_liabilitas)'))
            ->get();
    }

    public function getAkunInLiabilitas()
    {
        $userId = auth()->user()->id;
        return DB::table('liabilitas')
            ->select('liabilitas.no_reff', 'akun.no_reff', 'akun.nama_reff')
            ->join('akun', 'liabilitas.no_reff', '=', 'akun.no_reff')
            ->where('liabilitas.id_user', $userId)
            ->groupBy('akun.nama_reff')
            ->orderBy('akun.no_reff', 'ASC')
            ->get();
    }

    public function countAkunInLiabilitas()
    {
        $userId = auth()->user()->id;
        return DB::table('liabilitas')
            ->select('liabilitas.no_reff', 'akun.no_reff', 'akun.nama_reff')
            ->join('akun', 'liabilitas.no_reff', '=', 'akun.no_reff')
            ->where('liabilitas.id_user', $userId)
            ->groupBy('akun.nama_reff')
            ->orderBy('akun.no_reff', 'ASC')
            ->count();
    }

    public function getLiabilitasByNoReff($noReff)
    {
        $userId = auth()->user()->id;
        return DB::table('liabilitas')
            ->select('liabilitas.id_liabilitas', 'liabilitas.tgl_liabilitas', 'akun.nama_reff', 'liabilitas.no_reff', 'liabilitas.jenis_saldo', 'liabilitas.saldo', 'liabilitas.tgl_input')
            ->join('akun', 'liabilitas.no_reff', '=', 'akun.no_reff')
            ->where('liabilitas.no_reff', $noReff)
            ->where('liabilitas.id_user', $userId)
            ->orderBy('liabilitas.tgl_liabilitas', 'ASC')
            ->get();
    }

    public function getLiabilitasByNoReffMonthYear($noReff, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('liabilitas')
            ->select('liabilitas.id_liabilitas', 'liabilitas.tgl_liabilitas', 'akun.nama_reff', 'liabilitas.no_reff', 'liabilitas.jenis_saldo', 'liabilitas.saldo', 'liabilitas.tgl_input')
            ->join('akun', 'liabilitas.no_reff', '=', 'akun.no_reff')
            ->where('liabilitas.no_reff', $noReff)
            ->whereMonth('liabilitas.tgl_liabilitas', $bulan)
            ->whereYear('liabilitas.tgl_liabilitas', $tahun)
            ->where('liabilitas.id_user', $userId)
            ->orderBy('liabilitas.tgl_liabilitas', 'ASC')
            ->get();
    }

    public function getLiabilitasBydNoReffMonthYear($noReff, $bulan, $tahun, $jenisSaldo)
    {
        $userId = auth()->user()->id;
        return DB::table('liabilitas')
            ->select('liabilitas.id_liabilitas', 'liabilitas.tgl_liabilitas', 'akun.nama_reff', 'liabilitas.no_reff', 'liabilitas.jenis_saldo', 'liabilitas.saldo', 'liabilitas.tgl_input')
            ->join('akun', 'liabilitas.no_reff', '=', 'akun.no_reff')
            ->where('liabilitas.no_reff', $noReff)
            ->whereMonth('liabilitas.tgl_liabilitas', $bulan)
            ->whereYear('liabilitas.tgl_liabilitas', $tahun)
            ->where('liabilitas.jenis_saldo', $jenisSaldo)
            ->where('liabilitas.id_user', $userId)
            ->orderBy('liabilitas.tgl_liabilitas', 'ASC')
            ->get();
    }

    public function getLiabilitasByNoReffSaldo($noReff)
    {
        $userId = auth()->user()->id;
        return DB::table('liabilitas')
            ->select('liabilitas.jenis_saldo', 'liabilitas.saldo')
            ->join('akun', 'liabilitas.no_reff', '=', 'akun.no_reff')
            ->where('liabilitas.no_reff', $noReff)
            ->where('liabilitas.id_user', $userId)
            ->orderBy('liabilitas.tgl_liabilitas', 'ASC')
            ->get();
    }

    public function getLiabilitasByNoReffSaldoMonthYear($noReff, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('liabilitas')
            ->select('liabilitas.jenis_saldo', 'liabilitas.saldo')
            ->join('akun', 'liabilitas.no_reff', '=', 'akun.no_reff')
            ->where('liabilitas.no_reff', $noReff)
            ->whereMonth('liabilitas.tgl_liabilitas', $bulan)
            ->whereYear('liabilitas.tgl_liabilitas', $tahun)
            ->where('liabilitas.id_user', $userId)
            ->orderBy('liabilitas.tgl_liabilitas', 'ASC')
            ->get();
    }

    public function getLiabilitasJoinAkun()
    {
        $userId = auth()->user()->id;
        return DB::table('liabilitas')
            ->select('liabilitas.id_liabilitas', 'liabilitas.tgl_liabilitas', 'akun.nama_reff', 'liabilitas.no_reff', 'liabilitas.jenis_saldo', 'liabilitas.saldo', 'liabilitas.tgl_input')
            ->join('akun', 'liabilitas.no_reff', '=', 'akun.no_reff')
            ->where('liabilitas.id_user', $userId)
            ->orderBy('liabilitas.tgl_liabilitas', 'ASC')
            ->orderBy('liabilitas.tgl_input', 'ASC')
            ->orderBy('liabilitas.jenis_saldo', 'ASC')
            ->get();
    }

    public function getLiabilitasJoinAkunDetail($bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('liabilitas')
            ->select('liabilitas.id_liabilitas', 'liabilitas.tgl_liabilitas', 'akun.nama_reff', 'liabilitas.no_reff', 'liabilitas.jenis_saldo', 'liabilitas.saldo', 'liabilitas.tgl_input')
            ->join('akun', 'liabilitas.no_reff', '=', 'akun.no_reff')
            ->whereMonth('liabilitas.tgl_liabilitas', $bulan)
            ->whereYear('liabilitas.tgl_liabilitas', $tahun)
            ->where('liabilitas.id_user', $userId)
            ->orderBy('liabilitas.tgl_liabilitas', 'ASC')
            ->orderBy('liabilitas.tgl_input', 'ASC')
            ->orderBy('liabilitas.jenis_saldo', 'ASC')
            ->get();
    }

    public function getTotalSaldoDetail($jenisSaldo, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return Liabilitas::selectRaw('SUM(saldo) as total_saldo')
            ->whereMonth('tgl_liabilitas', $bulan)
            ->whereYear('tgl_liabilitas', $tahun)
            ->where('jenis_saldo', $jenisSaldo)
            ->where('id_user', $userId)
            ->first();
    }

    public function getTotalSaldo($jenisSaldo)
    {
        $userId = auth()->user()->id;
        return Liabilitas::selectRaw('SUM(saldo) as total_saldo')
            ->where('jenis_saldo', $jenisSaldo)
            ->where('id_user', $userId)
            ->first();
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ekuitas extends Model
{
    use HasFactory;

    protected $table = 'ekuitas';
    protected $primaryKey = 'id_ekuitas';
    
    protected $fillable = [
        'id_user',
        'no_reff',
        'tgl_ekuitas',
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

    public function getEkuitasById($id)
    {
        $userId = auth()->user()->id;
        return Ekuitas::where('id_ekuitas', $id)
            ->where('id_user', $userId)
            ->first();
    }

    public function getEkuitasByUserId()
    {
        $userId = auth()->user()->id;
        return Ekuitas::where('id_user', $userId)->get();
    }

    public function countEkuitasNoReff($noReff)
    {
        $userId = auth()->user()->id;
        return Ekuitas::where('no_reff', $noReff)
            ->where('id_user', $userId)
            ->count();
    }

    public function getEkuitasByYear()
    {
        $userId = auth()->user()->id;
        return Ekuitas::select(DB::raw('year(tgl_ekuitas) as year'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('year(tgl_ekuitas)'))
            ->get();
    }

    public function getEkuitasByYearAndMonth()
    {
        $userId = auth()->user()->id;
        return Ekuitas::select(DB::raw('month(tgl_ekuitas) as month, year(tgl_ekuitas) as year'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('month(tgl_ekuitas)'), DB::raw('year(tgl_ekuitas)'))
            ->get();
    }

    public function getAkunInEkuitas()
    {
        $userId = auth()->user()->id;
        return DB::table('ekuitas')
            ->select('ekuitas.no_reff', 'akun.no_reff', 'akun.nama_reff')
            ->join('akun', 'ekuitas.no_reff', '=', 'akun.no_reff')
            ->where('ekuitas.id_user', $userId)
            ->groupBy('akun.nama_reff')
            ->orderBy('akun.no_reff', 'ASC')
            ->get();
    }

    public function countAkunInEkuitas()
    {
        $userId = auth()->user()->id;
        return DB::table('ekuitas')
            ->select('ekuitas.no_reff', 'akun.no_reff', 'akun.nama_reff')
            ->join('akun', 'ekuitas.no_reff', '=', 'akun.no_reff')
            ->where('ekuitas.id_user', $userId)
            ->groupBy('akun.nama_reff')
            ->orderBy('akun.no_reff', 'ASC')
            ->count();
    }

    public function getEkuitasByNoReff($noReff)
    {
        $userId = auth()->user()->id;
        return DB::table('ekuitas')
            ->select('ekuitas.id_ekuitas', 'ekuitas.tgl_ekuitas', 'akun.nama_reff', 'ekuitas.no_reff', 'ekuitas.jenis_saldo', 'ekuitas.saldo', 'ekuitas.tgl_input')
            ->join('akun', 'ekuitas.no_reff', '=', 'akun.no_reff')
            ->where('ekuitas.no_reff', $noReff)
            ->where('ekuitas.id_user', $userId)
            ->orderBy('ekuitas.tgl_ekuitas', 'ASC')
            ->get();
    }

    public function getEkuitasByNoReffMonthYear($noReff, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('ekuitas')
            ->select('ekuitas.id_ekuitas', 'ekuitas.tgl_ekuitas', 'akun.nama_reff', 'ekuitas.no_reff', 'ekuitas.jenis_saldo', 'ekuitas.saldo', 'ekuitas.tgl_input')
            ->join('akun', 'ekuitas.no_reff', '=', 'akun.no_reff')
            ->where('ekuitas.no_reff', $noReff)
            ->whereMonth('ekuitas.tgl_ekuitas', $bulan)
            ->whereYear('ekuitas.tgl_ekuitas', $tahun)
            ->where('ekuitas.id_user', $userId)
            ->orderBy('ekuitas.tgl_ekuitas', 'ASC')
            ->get();
    }

    public function getEkuitasBydNoReffMonthYear($noReff, $bulan, $tahun, $jenisSaldo)
    {
        $userId = auth()->user()->id;
        return DB::table('ekuitas')
            ->select('ekuitas.id_ekuitas', 'ekuitas.tgl_ekuitas', 'akun.nama_reff', 'ekuitas.no_reff', 'ekuitas.jenis_saldo', 'ekuitas.saldo', 'ekuitas.tgl_input')
            ->join('akun', 'ekuitas.no_reff', '=', 'akun.no_reff')
            ->where('ekuitas.no_reff', $noReff)
            ->whereMonth('ekuitas.tgl_ekuitas', $bulan)
            ->whereYear('ekuitas.tgl_ekuitas', $tahun)
            ->where('ekuitas.jenis_saldo', $jenisSaldo)
            ->where('ekuitas.id_user', $userId)
            ->orderBy('ekuitas.tgl_ekuitas', 'ASC')
            ->get();
    }

    public function getEkuitasByNoReffSaldo($noReff)
    {
        $userId = auth()->user()->id;
        return DB::table('ekuitas')
            ->select('ekuitas.jenis_saldo', 'ekuitas.saldo')
            ->join('akun', 'ekuitas.no_reff', '=', 'akun.no_reff')
            ->where('ekuitas.no_reff', $noReff)
            ->where('ekuitas.id_user', $userId)
            ->orderBy('ekuitas.tgl_ekuitas', 'ASC')
            ->get();
    }

    public function getEkuitasByNoReffSaldoMonthYear($noReff, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('ekuitas')
            ->select('ekuitas.jenis_saldo', 'ekuitas.saldo')
            ->join('akun', 'ekuitas.no_reff', '=', 'akun.no_reff')
            ->where('ekuitas.no_reff', $noReff)
            ->whereMonth('ekuitas.tgl_ekuitas', $bulan)
            ->whereYear('ekuitas.tgl_ekuitas', $tahun)
            ->where('ekuitas.id_user', $userId)
            ->orderBy('ekuitas.tgl_ekuitas', 'ASC')
            ->get();
    }

    public function getEkuitasJoinAkun()
    {
        $userId = auth()->user()->id;
        return DB::table('ekuitas')
            ->select('ekuitas.id_ekuitas', 'ekuitas.tgl_ekuitas', 'akun.nama_reff', 'ekuitas.no_reff', 'ekuitas.jenis_saldo', 'ekuitas.saldo', 'ekuitas.tgl_input')
            ->join('akun', 'ekuitas.no_reff', '=', 'akun.no_reff')
            ->where('ekuitas.id_user', $userId)
            ->orderBy('ekuitas.tgl_ekuitas', 'ASC')
            ->orderBy('ekuitas.tgl_input', 'ASC')
            ->orderBy('ekuitas.jenis_saldo', 'ASC')
            ->get();
    }

    public function getEkuitasJoinAkunDetail($bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('ekuitas')
            ->select('ekuitas.id_ekuitas', 'ekuitas.tgl_ekuitas', 'akun.nama_reff', 'ekuitas.no_reff', 'ekuitas.jenis_saldo', 'ekuitas.saldo', 'ekuitas.tgl_input')
            ->join('akun', 'ekuitas.no_reff', '=', 'akun.no_reff')
            ->whereMonth('ekuitas.tgl_ekuitas', $bulan)
            ->whereYear('ekuitas.tgl_ekuitas', $tahun)
            ->where('ekuitas.id_user', $userId)
            ->orderBy('ekuitas.tgl_ekuitas', 'ASC')
            ->orderBy('ekuitas.tgl_input', 'ASC')
            ->orderBy('ekuitas.jenis_saldo', 'ASC')
            ->get();
    }

    public function getTotalSaldoDetail($jenisSaldo, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return Ekuitas::selectRaw('SUM(saldo) as total_saldo')
            ->whereMonth('tgl_ekuitas', $bulan)
            ->whereYear('tgl_ekuitas', $tahun)
            ->where('jenis_saldo', $jenisSaldo)
            ->where('id_user', $userId)
            ->first();
    }

    public function getTotalSaldo($jenisSaldo)
    {
        $userId = auth()->user()->id;
        return Ekuitas::selectRaw('SUM(saldo) as total_saldo')
            ->where('jenis_saldo', $jenisSaldo)
            ->where('id_user', $userId)
            ->first();
    }

}

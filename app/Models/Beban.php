<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Beban extends Model
{
    use HasFactory;

    protected $table = 'beban';
    protected $primaryKey = 'id_beban';
    
    protected $fillable = [
        'id_user',
        'no_reff',
        'tgl_beban',
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

    public function getBebanById($id)
    {
        $userId = auth()->user()->id;
        return Beban::where('id_beban', $id)
            ->where('id_user', $userId)
            ->first();
    }

    public function getBebanByUserId()
    {
        $userId = auth()->user()->id;
        return Beban::where('id_user', $userId)->get();
    }

    public function countBebanNoReff($noReff)
    {
        $userId = auth()->user()->id;
        return Beban::where('no_reff', $noReff)
            ->where('id_user', $userId)
            ->count();
    }

    public function getBebanByYear()
    {
        $userId = auth()->user()->id;
        return Beban::select(DB::raw('year(tgl_beban) as year'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('year(tgl_beban)'))
            ->get();
    }

    public function getBebanByYearAndMonth()
    {
        $userId = auth()->user()->id;
        return Beban::select(DB::raw('month(tgl_beban) as month, year(tgl_beban) as year'))
            ->where('id_user', $userId)
            ->groupBy(DB::raw('month(tgl_beban)'), DB::raw('year(tgl_beban)'))
            ->get();
    }

    public function getAkunInBeban()
    {
        $userId = auth()->user()->id;
        return DB::table('beban')
            ->select('beban.no_reff', 'akun.no_reff', 'akun.nama_reff')
            ->join('akun', 'beban.no_reff', '=', 'akun.no_reff')
            ->where('beban.id_user', $userId)
            ->groupBy('akun.nama_reff')
            ->orderBy('akun.no_reff', 'ASC')
            ->get();
    }

    public function countAkunInBeban()
    {
        $userId = auth()->user()->id;
        return DB::table('beban')
            ->select('beban.no_reff', 'akun.no_reff', 'akun.nama_reff')
            ->join('akun', 'beban.no_reff', '=', 'akun.no_reff')
            ->where('beban.id_user', $userId)
            ->groupBy('akun.nama_reff')
            ->orderBy('akun.no_reff', 'ASC')
            ->count();
    }

    public function getBebanByNoReff($noReff)
    {
        $userId = auth()->user()->id;
        return DB::table('beban')
            ->select('beban.id_beban', 'beban.tgl_beban', 'akun.nama_reff', 'beban.no_reff', 'beban.jenis_saldo', 'beban.saldo', 'beban.tgl_input')
            ->join('akun', 'beban.no_reff', '=', 'akun.no_reff')
            ->where('beban.no_reff', $noReff)
            ->where('beban.id_user', $userId)
            ->orderBy('beban.tgl_beban', 'ASC')
            ->get();
    }

    public function getBebanByNoReffMonthYear($noReff, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('beban')
            ->select('beban.id_beban', 'beban.tgl_beban', 'akun.nama_reff', 'beban.no_reff', 'beban.jenis_saldo', 'beban.saldo', 'beban.tgl_input')
            ->join('akun', 'beban.no_reff', '=', 'akun.no_reff')
            ->where('beban.no_reff', $noReff)
            ->whereMonth('beban.tgl_beban', $bulan)
            ->whereYear('beban.tgl_beban', $tahun)
            ->where('beban.id_user', $userId)
            ->orderBy('beban.tgl_beban', 'ASC')
            ->get();
    }

    public function getBebanBydNoReffMonthYear($noReff, $bulan, $tahun, $jenisSaldo)
    {
        $userId = auth()->user()->id;
        return DB::table('beban')
            ->select('beban.id_beban', 'beban.tgl_beban', 'akun.nama_reff', 'beban.no_reff', 'beban.jenis_saldo', 'beban.saldo', 'beban.tgl_input')
            ->join('akun', 'beban.no_reff', '=', 'akun.no_reff')
            ->where('beban.no_reff', $noReff)
            ->whereMonth('beban.tgl_beban', $bulan)
            ->whereYear('beban.tgl_beban', $tahun)
            ->where('beban.jenis_saldo', $jenisSaldo)
            ->where('beban.id_user', $userId)
            ->orderBy('beban.tgl_beban', 'ASC')
            ->get();
    }

    public function getBebanByNoReffSaldo($noReff)
    {
        $userId = auth()->user()->id;
        return DB::table('beban')
            ->select('beban.jenis_saldo', 'beban.saldo')
            ->join('akun', 'beban.no_reff', '=', 'akun.no_reff')
            ->where('beban.no_reff', $noReff)
            ->where('beban.id_user', $userId)
            ->orderBy('beban.tgl_beban', 'ASC')
            ->get();
    }

    public function getBebanByNoReffSaldoMonthYear($noReff, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('beban')
            ->select('beban.jenis_saldo', 'beban.saldo')
            ->join('akun', 'beban.no_reff', '=', 'akun.no_reff')
            ->where('beban.no_reff', $noReff)
            ->whereMonth('beban.tgl_beban', $bulan)
            ->whereYear('beban.tgl_beban', $tahun)
            ->where('beban.id_user', $userId)
            ->orderBy('beban.tgl_beban', 'ASC')
            ->get();
    }

    public function getBebanJoinAkun()
    {
        $userId = auth()->user()->id;
        return DB::table('beban')
            ->select('beban.id_beban', 'beban.tgl_beban', 'akun.nama_reff', 'beban.no_reff', 'beban.jenis_saldo', 'beban.saldo', 'beban.tgl_input')
            ->join('akun', 'beban.no_reff', '=', 'akun.no_reff')
            ->where('beban.id_user', $userId)
            ->orderBy('beban.tgl_beban', 'ASC')
            ->orderBy('beban.tgl_input', 'ASC')
            ->orderBy('beban.jenis_saldo', 'ASC')
            ->get();
    }

    public function getBebanJoinAkunDetail($bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return DB::table('beban')
            ->select('beban.id_beban', 'beban.tgl_beban', 'akun.nama_reff', 'beban.no_reff', 'beban.jenis_saldo', 'beban.saldo', 'beban.tgl_input')
            ->join('akun', 'beban.no_reff', '=', 'akun.no_reff')
            ->whereMonth('beban.tgl_beban', $bulan)
            ->whereYear('beban.tgl_beban', $tahun)
            ->where('beban.id_user', $userId)
            ->orderBy('beban.tgl_beban', 'ASC')
            ->orderBy('beban.tgl_input', 'ASC')
            ->orderBy('beban.jenis_saldo', 'ASC')
            ->get();
    }

    public function getTotalSaldoDetail($jenisSaldo, $bulan, $tahun)
    {
        $userId = auth()->user()->id;
        return Beban::selectRaw('SUM(saldo) as total_saldo')
            ->whereMonth('tgl_beban', $bulan)
            ->whereYear('tgl_beban', $tahun)
            ->where('jenis_saldo', $jenisSaldo)
            ->where('id_user', $userId)
            ->first();
    }

    public function getTotalSaldo($jenisSaldo)
    {
        $userId = auth()->user()->id;
        return Beban::selectRaw('SUM(saldo) as total_saldo')
            ->where('jenis_saldo', $jenisSaldo)
            ->where('id_user', $userId)
            ->first();
    }

}

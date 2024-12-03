<?php
// File: App/Models/Akun.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;

    protected $table = 'akun';
    protected $primaryKey = 'no_reff';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_reff',
        'nama_reff',
        'keterangan',
        'id_user'
    ];

    protected $guarded = [];  // Pastikan tidak ada field yang di-guard

    // Pastikan id_user selalu diisi saat create
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->id_user && auth()->check()) {
                $model->id_user = auth()->id();
            }
        });
    }

    public static function getValidationRules()
    {
        return [
            'no_reff' => 'required|string|unique:akun,no_reff',
            'nama_reff' => 'required|string|unique:akun,nama_reff',
            'keterangan' => 'required|string',
        ];
    }

    // Method lainnya tetap sama seperti sebelumnya
    public static function getAkunByUserId($userId)
    {
        return self::where('id_user', $userId)->get();
    }

    public static function getAkunByNo($no_reff)
    {
        return self::where('no_reff', $no_reff)->first();
    }

    public static function countAkunByNama($nama)
    {
        return self::where('nama_reff', $nama)->count();
    }

    public static function countAkunByNoReff($no_reff)
    {
        return self::where('no_reff', $no_reff)->count();
    }

    public static function insertAkun($data) {
        return self::create([
            'no_reff' => $data['no_reff'],
            'nama_reff' => $data['nama_reff'],
            'keterangan' => $data['keterangan'],
            'id_user' => auth()->id(),  // Pastikan menggunakan auth()->id()
        ]);
    }

    public static function updateAkun($no_reff, $data)
    {
        $data['id_user'] = auth()->id();  // Pastikan id_user selalu terisi saat update
        return self::where('no_reff', $no_reff)->update($data);
    }

    public static function deleteAkun($no_reff)
    {
        return self::where('no_reff', $no_reff)->delete();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function jurnal()
    {
        return $this->hasMany(Jurnal::class, 'no_reff', 'no_reff');
    }

    
    // Scope untuk mengambil data berdasarkan user_id
    public function scopeForUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }
}
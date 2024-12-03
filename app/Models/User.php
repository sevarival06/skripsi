<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_user';
    
    protected $fillable = [
        'nama_usaha',
        'role',
        'alamat',
        'email',
        'username',
        'password',
        'last_login'
    ];

    protected $hidden = [
        'password',
    ];

    public function beban()
    {
        return $this->hasMany(Beban::class, 'id_user');
    }

    public function pendapatan()
    {
        return $this->hasMany(Pendapatan::class, 'id_user');
    }

    public function transaksi()
    {
        return $this->hasMany(Jurnal::class, 'id_user');
    }

    public function liabilitas()
    {
        return $this->hasMany(Liabilitas::class, 'id_user');
    }

    public function ekuitas()
    {
        return $this->hasMany(Ekuitas::class, 'id_user');
    }

    public function akun()
    {
        return $this->hasMany(Akun::class, 'id_user');
    }
    
}

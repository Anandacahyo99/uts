<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Penting untuk Auth/Sanctum
use Laravel\Sanctum\HasApiTokens;
use Database\Factories\MahasiswaFactory;

class Mahasiswa extends Authenticatable
{
    use HasApiTokens, HasFactory;

    // Menghubungkan model ini ke tabel 'mahasiswa'
    protected static function newFactory(): MahasiswaFactory 
    {
        return MahasiswaFactory::new();
    }

    protected $table = 'mahasiswa'; 
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
}

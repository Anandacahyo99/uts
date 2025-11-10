<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MahasiswaController;

// --- A. ROUTE PUBLIK (Tidak Perlu Token) ---
// Route Register dan Login yang menggunakan MahasiswaController
Route::post('/mahasiswa/register', [MahasiswaController::class, 'register']);
Route::post('/login', [MahasiswaController::class, 'login']);

// --- B. ROUTE TERPROTEKSI (Membutuhkan Bearer Token) ---
// Gunakan guard 'auth:sanctum' standar, karena sudah diarahkan ke provider 'mahasiswas' di config/auth.php
Route::middleware('auth:sanctum')->group(function () {
    
    // 1. Mengambil Profil Mahasiswa yang sedang Login (Sesuai dengan token)
    Route::get('/mahasiswa/profile', function (Request $request) {
        return $request->user(); // Mengembalikan objek Mahasiswa yang diotentikasi
    });

    // 2. Mengambil Semua Data Mahasiswa
    Route::get('/mahasiswa/all', [MahasiswaController::class, 'getAllMahasiswa']);
    
    // 3. Route /user (standar Laravel, mengembalikan Mahasiswa)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
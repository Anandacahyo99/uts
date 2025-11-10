<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash; // <-- PERBAIKAN 2: Import Facade Hash

class MahasiswaController extends Controller
{
    // Pastikan Anda juga mengimport class Mahasiswa di file ini
    // ...

    public function register(Request $request)
    {
        // 1. Validasi Input JSON (Perhatikan: Tabel tujuan validasi DIUBAH)
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|unique:mahasiswa', // <-- DIUBAH: Cek keunikan di tabel 'mahasiswa'
            'password' => 'required|string|min:6',
        ]);

        // 2. Buat Mahasiswa & Hashing Password
        // MENGGUNAKAN MODEL MAHASISWA BARU
        $mahasiswa = Mahasiswa::create([ 
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Buat Token Sanctum
        $token = $mahasiswa->createToken('mahasiswa-token')->plainTextToken; // <-- Menggunakan $mahasiswa

        // 4. Respon JSON
        return response()->json([
            'status' => 'success',
            'user' => $mahasiswa,
            'token' => $token, 
        ], 201); 
    }

    public function login(Request $request)
    {
        // 1. Validasi Input Kredensial
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba Otentikasi dengan GUARD 'mahasiswa'
        // Ini mengatasi error 'RequestGuard::attempt does not exist'
        if (! Auth::guard('mahasiswa')->attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok dengan catatan kami.'],
            ]);
        }

        // 3. Jika Berhasil, Ambil User (Mahasiswa)
        $user = Auth::guard('mahasiswa')->user(); // Mengambil Mahasiswa yang terotentikasi

        // Periksa untuk memastikan objek yang diambil adalah Model Mahasiswa yang benar
        if ($user instanceof Mahasiswa) {
            
            // 4. Buat Token Sanctum Baru (Kunci Akses)
            $token = $user->createToken('mahasiswa-token')->plainTextToken;
            
            // 5. Ambil Semua Data Mahasiswa untuk ditampilkan di respons
            $semua_mahasiswa = Mahasiswa::all(); 

            // 6. Respon JSON Final (Status 200 OK)
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil. Token baru diterbitkan.',
                'token' => $token,
            ], 200);
        }

        // Fallback untuk kegagalan tak terduga
        return response()->json(['message' => 'Gagal mendapatkan data pengguna setelah otentikasi.'], 500);
    }

    public function getAllMahasiswa(Request $request)
    {
        // Secara teknis, kita tidak perlu memeriksa $request->user() di sini, 
        // karena middleware 'auth:sanctum' sudah memastikan user terautentikasi 
        // sebelum kode ini dijalankan.

        try {
            // Mengambil semua record dari tabel 'mahasiswa'
            $data_all = \App\Models\Mahasiswa::all(); 

            // Mengembalikan respons sukses dengan data
            return response()->json([
                'status' => 'success',
                'message' => 'Data semua Mahasiswa berhasil diambil.',
                'data' => $data_all,
            ], 200);

        } catch (\Exception $e) {
            // Error handling jika ada masalah database
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data dari database.',
                'error_detail' => $e->getMessage(),
            ], 500);
        }
    }

   
}
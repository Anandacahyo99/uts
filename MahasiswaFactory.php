<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // PASTIKAN SEMUA KOLOM NOT NULL ADA DI SINI:
            'name' => $this->faker->name(), // <-- HARUS ADA
            'email' => $this->faker->unique()->safeEmail(), // <-- HARUS ADA
            'email_verified_at' => now(), 
            'password' => Hash::make('password'), // <-- HARUS ADA & DI-HASH
            'remember_token' => Str::random(10),
        ];
    }
}

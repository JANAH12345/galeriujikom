<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Petugas;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        if (!Petugas::where('username','admin')->exists()) {
            Petugas::create([
                'username' => 'admin',
                'password' => 'rahasia123',
            ]);
        }
    }
}

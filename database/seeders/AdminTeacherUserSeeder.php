<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTeacherUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name' => 'Admin SIJAGO',
            'email' => 'admin@sijago.com', // Ganti dengan email admin yang valid
            'password' => Hash::make('password'), // Ganti dengan password yang kuat
            'role' => 'admin',
        ]);

        // Akun Guru
        User::create([
            'name' => 'Guru SIJAGO',
            'email' => 'guru@sijago.com', // Ganti dengan email guru yang valid
            'password' => Hash::make('password'), // Ganti dengan password yang kuat
            'role' => 'teacher',
        ]);
    }
}
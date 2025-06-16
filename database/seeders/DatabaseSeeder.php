<?php

namespace Database\Seeders;

use Database\Seeders\AdminTeacherUserSeeder; // Import seeder Anda
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminTeacherUserSeeder::class, // Panggil seeder ini
        ]);
    }
}

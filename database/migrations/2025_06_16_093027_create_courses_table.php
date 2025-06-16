<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Mata Pelajaran (e.g., Matematika, Fisika)
            $table->text('description')->nullable(); // Deskripsi mata pelajaran
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); // Guru yang mengajar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
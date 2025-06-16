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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // Terkait dengan mata pelajaran
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Guru yang mengunggah
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable(); // Path file yang diunggah
            $table->string('file_type')->nullable(); // Tipe file (pdf, docx, mp4, etc.)
            $table->string('link')->nullable(); // Jika berupa link eksternal (misal YouTube)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
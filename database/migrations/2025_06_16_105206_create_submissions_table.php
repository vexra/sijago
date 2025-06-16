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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siswa yang mengumpulkan
            $table->text('content')->nullable(); // Jawaban teks (opsional)
            $table->string('file_path')->nullable(); // File lampiran (opsional)
            $table->integer('grade')->nullable(); // Nilai tugas
            $table->text('feedback')->nullable(); // Umpan balik dari guru
            $table->timestamps(); // submitted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
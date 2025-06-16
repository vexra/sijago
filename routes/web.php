<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        // Jika Anda ingin mengarahkan langsung dari sini ke dashboard peran masing-masing
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        } else { // Asumsi default adalah student jika tidak ada peran lain
            return redirect()->route('student.dashboard');
        }
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute hanya untuk Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        // Tambahkan rute admin lainnya
    });

    // Rute hanya untuk Guru
    Route::middleware(['role:teacher'])->group(function () {
        Route::get('/teacher/dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');
        // Tambahkan rute guru lainnya
    });

    // Rute hanya untuk Siswa (bisa dilindungi atau tidak, tergantung kebutuhan)
    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
        // Tambahkan rute siswa lainnya
    });
});

require __DIR__.'/auth.php';

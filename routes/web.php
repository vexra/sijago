<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;

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

        // Rute untuk Manajemen Pengguna
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::patch('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        // Rute untuk update role (opsional, bisa digabung ke update)
        Route::patch('/admin/users/{user}/update-role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');

        // Rute untuk Manajemen Mata Pelajaran
        Route::get('/admin/courses', [CourseController::class, 'index'])->name('admin.courses.index');
        Route::get('/admin/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
        Route::post('/admin/courses', [CourseController::class, 'store'])->name('admin.courses.store');
        Route::get('/admin/courses/{course}/edit', [CourseController::class, 'edit'])->name('admin.courses.edit');
        Route::patch('/admin/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
        Route::delete('/admin/courses/{course}', [CourseController::class, 'destroy'])->name('admin.courses.destroy');
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

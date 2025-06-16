<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard'); // Pastikan Anda memiliki view ini
    }

    /**
     * Show the form for managing student enrollments in courses.
     */
    public function manageStudentCourses()
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        // Fetch current enrollments for display (optional, for more detailed view)
        // For simplicity, we'll fetch them on the fly in the view if needed.

        return view('admin.student_courses.index', compact('students', 'courses'));
    }

    /**
     * Enroll a student into a course.
     */
    public function enrollStudent(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:users,id', Rule::in(User::where('role', 'student')->pluck('id')->toArray())],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $student = User::find($request->student_id);
        $course = Course::find($request->course_id);

        if (!$student->enrolledCourses->contains($course->id)) {
            $student->enrolledCourses()->attach($course->id);
            return redirect()->back()->with('success', 'Siswa berhasil didaftarkan ke mata pelajaran.');
        } else {
            return redirect()->back()->with('info', 'Siswa sudah terdaftar di mata pelajaran ini.');
        }
    }

    /**
     * Unenroll a student from a course.
     */
    public function unenrollStudent(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:users,id', Rule::in(User::where('role', 'student')->pluck('id')->toArray())],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $student = User::find($request->student_id);
        $course = Course::find($request->course_id);

        if ($student->enrolledCourses->contains($course->id)) {
            $student->enrolledCourses()->detach($course->id);
            return redirect()->back()->with('success', 'Siswa berhasil dikeluarkan dari mata pelajaran.');
        } else {
            return redirect()->back()->with('info', 'Siswa tidak terdaftar di mata pelajaran ini.');
        }
    }

    // Metode untuk melihat detail pendaftaran per siswa (opsional, untuk tampilan yang lebih baik)
    public function showStudentEnrollments(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        $enrolledCourses = $student->enrolledCourses()->get();
        $availableCourses = Course::whereNotIn('id', $enrolledCourses->pluck('id'))->get();

        return view('admin.student_courses.show_enrollments', compact('student', 'enrolledCourses', 'availableCourses'));
    }
}
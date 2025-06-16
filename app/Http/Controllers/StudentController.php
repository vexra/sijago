<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Submission; // Akan digunakan nanti

class StudentController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $enrolledCourses = $student->enrolledCourses()->with('teacher')->get(); // Load guru pengajar

        $upcomingAssignments = Assignment::whereIn('course_id', $enrolledCourses->pluck('id'))
                                        ->where('due_date', '>=', now())
                                        ->whereDoesntHave('submissions', function ($query) use ($student) { // Perubahan di sini
                                            $query->where('user_id', $student->id);
                                        }) // Belum dikumpulkan
                                        ->orderBy('due_date')
                                        ->limit(5)
                                        ->get();

        $recentMaterials = Material::whereIn('course_id', $enrolledCourses->pluck('id'))
                                    ->latest()
                                    ->limit(5)
                                    ->get();

        return view('student.dashboard', compact('enrolledCourses', 'upcomingAssignments', 'recentMaterials'));
    }

    public function myCourses()
    {
        $enrolledCourses = Auth::user()->enrolledCourses()->with('teacher')->paginate(10);
        return view('student.courses.index', compact('enrolledCourses'));
    }

    public function showCourse(Course $course)
    {
        // Pastikan siswa terdaftar di mata pelajaran ini
        if (!Auth::user()->enrolledCourses->contains($course->id)) {
            abort(403, 'Anda tidak terdaftar di mata pelajaran ini.');
        }

        $materials = $course->materials()->latest()->get();
        $assignments = $course->assignments()->with(['submissions' => function($query) {
            $query->where('user_id', Auth::id());
        }])->latest()->get();
        // Nanti bisa tambahkan quizzes/exams juga

        return view('student.courses.show', compact('course', 'materials', 'assignments'));
    }

    public function showAssignment(Assignment $assignment)
    {
        // Pastikan siswa terdaftar di mata pelajaran tugas ini
        if (!Auth::user()->enrolledCourses->contains($assignment->course_id)) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $submission = $assignment->submissions->firstWhere('user_id', Auth::id()); // Cek jika sudah ada submission
        return view('student.assignments.show', compact('assignment', 'submission'));
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        // Pastikan siswa terdaftar di mata pelajaran tugas ini
        if (!Auth::user()->enrolledCourses->contains($assignment->course_id)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Cek jika tugas sudah lewat deadline
        if (now()->isAfter($assignment->due_date)) {
            return redirect()->back()->with('error', 'Tugas sudah melewati batas waktu pengumpulan.');
        }

        $request->validate([
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:20480'], // Max 20MB
        ]);

        // Cek apakah siswa sudah pernah mengumpulkan
        $submission = $assignment->submissions->firstWhere('user_id', Auth::id());

        $filePath = $submission ? $submission->file_path : null;

        if ($request->hasFile('file')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('submissions', 'public');
        } elseif ($request->boolean('remove_file')) { // Untuk menghapus file saat edit
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
                $filePath = null;
            }
        }

        $data = [
            'content' => $request->content,
            'file_path' => $filePath,
        ];

        if ($submission) {
            $submission->update($data);
            $message = 'Tugas berhasil diperbarui!';
        } else {
            $assignment->submissions()->create([
                'user_id' => Auth::id(),
                'content' => $request->content,
                'file_path' => $filePath,
            ]);
            $message = 'Tugas berhasil dikumpulkan!';
        }

        return redirect()->route('student.assignments.show', $assignment)->with('success', $message);
    }
}

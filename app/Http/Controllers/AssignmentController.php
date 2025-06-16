<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the assignments for a teacher's courses.
     */
    public function index()
    {
        $teacherCoursesIds = Auth::user()->courses->pluck('id');
        $assignments = Assignment::whereIn('course_id', $teacherCoursesIds)
                                ->with('course')
                                ->latest()
                                ->paginate(10);

        return view('teacher.assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create()
    {
        $courses = Auth::user()->courses; // Hanya mata pelajaran yang diajar guru ini
        if ($courses->isEmpty()) {
            return redirect()->route('teacher.dashboard')->with('error', 'Anda belum ditugaskan untuk mengajar mata pelajaran apapun. Silakan hubungi admin.');
        }
        return view('teacher.assignments.create', compact('courses'));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => ['required', 'exists:courses,id', Rule::in(Auth::user()->courses->pluck('id')->toArray())],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:20480'], // Max 20MB
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        Assignment::create([
            'course_id' => $request->course_id,
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('teacher.assignments.index')->with('success', 'Tugas berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(Assignment $assignment)
    {
        // Pastikan guru yang mengedit adalah guru yang memberikan tugas
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $courses = Auth::user()->courses;
        return view('teacher.assignments.edit', compact('assignment', 'courses'));
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        // Pastikan guru yang mengedit adalah guru yang memberikan tugas
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'course_id' => ['required', 'exists:courses,id', Rule::in(Auth::user()->courses->pluck('id')->toArray())],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:20480'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $filePath = $assignment->file_path;

        if ($request->hasFile('file')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('assignments', 'public');
        } elseif ($request->boolean('remove_file')) { // Checkbox untuk menghapus file
             if ($filePath) {
                Storage::disk('public')->delete($filePath);
                $filePath = null;
            }
        }

        $assignment->update([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('teacher.assignments.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(Assignment $assignment)
    {
        // Pastikan guru yang menghapus adalah guru yang memberikan tugas
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($assignment->file_path) {
            Storage::disk('public')->delete($assignment->file_path);
        }
        $assignment->delete();
        return redirect()->route('teacher.assignments.index')->with('success', 'Tugas berhasil dihapus!');
    }
}
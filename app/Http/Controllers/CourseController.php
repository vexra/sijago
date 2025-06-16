<?php

// app/Http/Controllers/CourseController.php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User; // Untuk memilih guru
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with('teacher')->paginate(10); // Load guru yang mengajar
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')->get(); // Ambil semua user dengan role 'teacher'
        return view('admin.courses.create', compact('teachers'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:courses'],
            'description' => ['nullable', 'string'],
            'teacher_id' => ['nullable', 'exists:users,id', Rule::exists('users', 'id')->where(function ($query) {
                $query->where('role', 'teacher');
            })],
        ]);

        Course::create($request->all());

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully!');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.courses.edit', compact('course', 'teachers'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('courses')->ignore($course->id)],
            'description' => ['nullable', 'string'],
            'teacher_id' => ['nullable', 'exists:users,id', Rule::exists('users', 'id')->where(function ($query) {
                $query->where('role', 'teacher');
            })],
        ]);

        $course->update($request->all());

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully!');
    }
}

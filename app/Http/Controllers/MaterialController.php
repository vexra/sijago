<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    /**
     * Display a listing of the materials for a teacher's courses.
     */
    public function index()
    {
        $teacherCoursesIds = Auth::user()->courses->pluck('id');
        $materials = Material::whereIn('course_id', $teacherCoursesIds)
                            ->with('course')
                            ->latest()
                            ->paginate(10);

        return view('teacher.materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new material.
     */
    public function create()
    {
        $courses = Auth::user()->courses; // Hanya mata pelajaran yang diajar guru ini
        if ($courses->isEmpty()) {
            return redirect()->route('teacher.dashboard')->with('error', 'Anda belum ditugaskan untuk mengajar mata pelajaran apapun. Silakan hubungi admin.');
        }
        return view('teacher.materials.create', compact('courses'));
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => ['required', 'exists:courses,id', Rule::in(Auth::user()->courses->pluck('id')->toArray())],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,mp4,mov,avi', 'max:20480'], // Max 20MB
            'link' => ['nullable', 'url', 'max:255'],
        ]);

        $filePath = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
            $fileType = $request->file('file')->getClientMimeType();
        }

        Material::create([
            'course_id' => $request->course_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'link' => $request->link,
        ]);

        return redirect()->route('teacher.materials.index')->with('success', 'Materi berhasil diunggah!');
    }

    /**
     * Show the form for editing the specified material.
     */
    public function edit(Material $material)
    {
        // Pastikan guru yang mengedit adalah guru yang mengunggah atau yang mengajar mata pelajaran tersebut
        if ($material->user_id !== Auth::id() && !Auth::user()->courses->contains('id', $material->course_id)) {
            abort(403, 'Unauthorized action.');
        }
        $courses = Auth::user()->courses;
        return view('teacher.materials.edit', compact('material', 'courses'));
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, Material $material)
    {
        // Pastikan guru yang mengedit adalah guru yang mengunggah atau yang mengajar mata pelajaran tersebut
        if ($material->user_id !== Auth::id() && !Auth::user()->courses->contains('id', $material->course_id)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'course_id' => ['required', 'exists:courses,id', Rule::in(Auth::user()->courses->pluck('id')->toArray())],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,mp4,mov,avi', 'max:20480'],
            'link' => ['nullable', 'url', 'max:255'],
        ]);

        $filePath = $material->file_path;
        $fileType = $material->file_type;

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('materials', 'public');
            $fileType = $request->file('file')->getClientMimeType();
        } elseif ($request->boolean('remove_file')) { // Checkbox untuk menghapus file
             if ($filePath) {
                Storage::disk('public')->delete($filePath);
                $filePath = null;
                $fileType = null;
            }
        }


        $material->update([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'link' => $request->link,
        ]);

        return redirect()->route('teacher.materials.index')->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy(Material $material)
    {
        // Pastikan guru yang menghapus adalah guru yang mengunggah atau yang mengajar mata pelajaran tersebut
        if ($material->user_id !== Auth::id() && !Auth::user()->courses->contains('id', $material->course_id)) {
            abort(403, 'Unauthorized action.');
        }

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();
        return redirect()->route('teacher.materials.index')->with('success', 'Materi berhasil dihapus!');
    }
}

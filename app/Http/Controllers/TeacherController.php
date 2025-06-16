<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Material;
use App\Models\Assignment;

class TeacherController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $courses = $teacher->courses;

        $recentMaterials = Material::whereIn('course_id', $courses->pluck('id'))
                                    ->latest()
                                    ->limit(5)
                                    ->get();

        $upcomingAssignments = Assignment::whereIn('course_id', $courses->pluck('id'))
                                        ->where('due_date', '>=', now())
                                        ->orderBy('due_date')
                                        ->limit(5)
                                        ->get();

        return view('teacher.dashboard', compact('courses', 'recentMaterials', 'upcomingAssignments'));
    }
}

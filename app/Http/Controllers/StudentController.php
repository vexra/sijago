<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('student.dashboard'); // Pastikan Anda memiliki view ini
    }
}

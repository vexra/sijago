<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('teacher.dashboard'); // Pastikan Anda memiliki view ini
    }
}

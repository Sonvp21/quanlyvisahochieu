<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        return view('student.dashboard', compact('student'));
    }
}




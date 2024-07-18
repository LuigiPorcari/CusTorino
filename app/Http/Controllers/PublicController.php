<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function homepage() {
        return view('homepage');
    }

    public function test() {
        $students = Student::all();
        return view('test', compact('students'));
    }
}

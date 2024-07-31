<?php

namespace App\Http\Controllers;

use App\Models\Alias;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.student.student-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('student')->attempt($credentials)) {
            return view('homepage');
        }

        return redirect()->back()->withErrors(['email' => 'Le credenziali non sono corrette.']);
    }

    public function showRegistrationForm()
    {
        return view('auth.student.student-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Student::create([
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            // 'level' => $request->level,
            // 'documentation' => $request->documentation,
        ]);

        return redirect()->route('students.login');
    }

    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect('/');
    }

    public function destroy($id)
{
    $student = Student::findOrFail($id);

    // Rimuovere i record dipendenti nella tabella pivot alias_student
    $student->aliases()->detach();
    // Rimuovere i record dipendenti nella tabella pivot group_student
    $student->groups()->detach();

    $student->delete();

    return redirect()->route('admin.dashboard.student')->with('success', 'Studente eliminato con successo.');
}
}

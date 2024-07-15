<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TrainerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.trainer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('trainer')->attempt($credentials)) {
            return view('homepage');
        }

        return redirect()->back()->withErrors(['email' => 'Le credenziali non sono corrette.']);
    }

    public function showRegistrationForm()
    {
        return view('auth.trainer-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Trainer::create([
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('trainer.login');
    }

    public function logout()
    {
        Auth::guard('trainer')->logout();
        return redirect('/');
    }
}
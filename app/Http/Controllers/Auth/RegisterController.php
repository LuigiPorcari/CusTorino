<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Mostra il form di registrazione per i corsisti
    public function showCorsistaRegistrationForm()
    {
        return view('auth.register-corsista');
    }

    // Registra un corsista
    public function registerCorsista(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'genere' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'cognome' => $request->cognome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_corsista' => true,
            'genere' => $request->genere,
        ]);

        return redirect()->route('login');
    }

    // Mostra il form di registrazione per i trainer
    public function showTrainerRegistrationForm()
    {
        return view('auth.register-trainer');
    }

    // Registra un trainer
    public function registerTrainer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'cognome' => $request->cognome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_trainer' => true,
        ]);

        return redirect()->route('login');
    }

    // Mostra il form di registrazione per gli admin
    public function showAdminRegistrationForm()
    {
        return view('auth.register-admin');
    }

    // Registra un admin
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'cognome' => $request->cognome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
        ]);

        return redirect()->route('login');
    }
}

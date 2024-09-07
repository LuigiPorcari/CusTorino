<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Mostra il form per cambiare la password.
     */
    public function showChangePasswordForm()
    {
        return view('auth.passwords.change');
    }

    /**
     * Gestisce la modifica della password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verifica che la vecchia password sia corretta
        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return back()->withErrors(['old_password' => 'La vecchia password non è corretta']);
        }

        // Aggiorna la password
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('homepage')->with('success', 'Password cambiata con successo!');
    }
}
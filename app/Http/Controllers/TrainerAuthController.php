<?php

namespace App\Http\Controllers;

use App\Models\Alias;
use App\Models\Group;
use App\Models\Trainer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TrainerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.trainer.trainer-login');
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
        return view('auth.trainer.trainer-register');
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

        return redirect()->route('homepage');
    }

    public function logout()
    {
        Auth::guard('trainer')->logout();
        return redirect('/');
    }

    public function destroy($id)
    {
        $trainer = Trainer::findOrFail($id);

        // Aggiornare o rimuovere i record dipendenti
        Alias::where('primo_allenatore_id', $id)->update(['primo_allenatore_id' => null]);
        Alias::where('secondo_allenatore_id', $id)->update(['secondo_allenatore_id' => null]);
        Group::where('primo_allenatore_id', $id)->update(['primo_allenatore_id' => null]);
        Group::where('secondo_allenatore_id', $id)->update(['secondo_allenatore_id' => null]);

        $trainer->delete();

        return redirect()->route('homepage')->with('success', 'Trainer eliminato con successo.');
    }
}
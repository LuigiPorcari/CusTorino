<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Alias;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return view('homepage');
        }

        return redirect()->back()->withErrors(['email' => 'Le credenziali non sono corrette.']);
    }

    public function showRegistrationForm()
    {
        return view('auth.admin-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('homepage');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('homepage')->with('success', 'Admin eliminato con successo.');
    }
}
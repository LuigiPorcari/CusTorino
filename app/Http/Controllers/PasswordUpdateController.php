<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PasswordUpdateController extends Controller
{
    use ValidatesRequests;
    public function showTrainerPasswordChangeForm()
    {
        return view('auth.trainer.passwords.change');
    }

    public function updateTrainerPassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::guard('trainer')->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'La vecchia password non è corretta.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('trainer.dashboard')->with('status', 'Password aggiornata con successo.');
    }

    public function showStudentPasswordChangeForm()
    {
        return view('auth.student.passwords.change');
    }

    public function updateStudentPassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::guard('student')->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'La vecchia password non è corretta.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('student.dashboard')->with('status', 'Password aggiornata con successo.');
    }

    public function showAdminPasswordChangeForm()
    {
        return view('auth.admin.passwords.change');
    }

    public function updateAdminPassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::guard('admin')->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'La vecchia password non è corretta.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.dashboard')->with('status', 'Password aggiornata con successo.');
    }
}

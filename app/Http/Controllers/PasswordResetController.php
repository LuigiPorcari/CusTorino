<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ResetPasswordNotification;

class PasswordResetController extends Controller
{
    // Trainer methods
    public function showTrainerLinkRequestForm()
    {
        return view('auth.trainer.passwords.email');
    }

    public function sendTrainerResetLinkEmail(Request $request)
    {
        return $this->sendResetLinkEmail($request, 'trainers');
    }

    public function showTrainerResetForm($token)
    {
        return view('auth.trainer.passwords.reset', ['token' => $token]);
    }

    public function resetTrainerPassword(Request $request)
    {
        return $this->resetPassword($request, 'trainers');
    }

    // Student methods
    public function showStudentLinkRequestForm()
    {
        return view('auth.student.passwords.email');
    }

    public function sendStudentResetLinkEmail(Request $request)
    {
        return $this->sendResetLinkEmail($request, 'students');
    }

    public function showStudentResetForm($token)
    {
        return view('auth.student.passwords.reset', ['token' => $token]);
    }

    public function resetStudentPassword(Request $request)
    {
        return $this->resetPassword($request, 'students');
    }

    // Admin methods
    public function showAdminLinkRequestForm()
    {
        return view('auth.admin.passwords.email');
    }

    public function sendAdminResetLinkEmail(Request $request)
    {
        return $this->sendResetLinkEmail($request, 'admins');
    }

    public function showAdminResetForm($token)
    {
        return view('auth.admin.passwords.reset', ['token' => $token]);
    }

    public function resetAdminPassword(Request $request)
    {
        return $this->resetPassword($request, 'admins');
    }

    // Common methods
    protected function sendResetLinkEmail(Request $request, $guard)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker($guard)->sendResetLink(
            $request->only('email'),
            function ($user, $token) use ($guard) {
                $user->notify(new ResetPasswordNotification($token, $guard));
            }
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    protected function resetPassword(Request $request, $guard)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker($guard)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route($guard . '.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}

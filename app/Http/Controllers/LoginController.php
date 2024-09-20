<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alias;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Mostra il form di login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Gestisce il login dell'utente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (Auth::attempt($this->credentials($request), $request->filled('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            if (Auth::user()->is_trainer) {
                return redirect()->route('trainer.dashboard');
            }
            if (Auth::user()->is_corsista) {
                return redirect()->route('student.dashboard');
            }
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Valida i dati del form di login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }

    /**
     * Ottiene le credenziali da utilizzare per l'autenticazione.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    /**
     * Effettua il logout dell'utente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function adminDestroyStudent($id)
    {
        $student = User::findOrFail($id);

        // Rimuovere i record dipendenti nella tabella pivot alias_student
        $student->aliases()->detach();
        // Rimuovere i record dipendenti nella tabella pivot group_student
        $student->groups()->detach();
        if ($student->is_corsista) {
            // Aggiornare o rimuovere i record dipendenti
            Alias::where('primo_allenatore_id', $id)->update(['primo_allenatore_id' => null]);
            Alias::where('secondo_allenatore_id', $id)->update(['secondo_allenatore_id' => null]);
            Group::where('primo_allenatore_id', $id)->update(['primo_allenatore_id' => null]);
            Group::where('secondo_allenatore_id', $id)->update(['secondo_allenatore_id' => null]);
        }

        $student->delete();

        return redirect()->route('admin.dashboard.student')->with('success', 'Studente eliminato con successo.');
    }

    public function destroyTrainer($id)
    {
        $trainer = User::findOrFail($id);

        // Aggiornare o rimuovere i record dipendenti
        Alias::where('primo_allenatore_id', $id)->update(['primo_allenatore_id' => null]);
        Alias::where('secondo_allenatore_id', $id)->update(['secondo_allenatore_id' => null]);
        Group::where('primo_allenatore_id', $id)->update(['primo_allenatore_id' => null]);
        Group::where('secondo_allenatore_id', $id)->update(['secondo_allenatore_id' => null]);
        if ($trainer->is_corsista) {
            // Rimuovere i record dipendenti nella tabella pivot alias_student
            $trainer->aliases()->detach();
            // Rimuovere i record dipendenti nella tabella pivot group_student
            $trainer->groups()->detach();
        }

        $trainer->delete();

        return redirect()->route('admin.dashboard.trainer')->with('success', 'Trainer eliminato con successo.');
    }

    public function destroyAdmin($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Admin eliminato con successo.');
    }
}

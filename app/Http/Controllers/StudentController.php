<?php

namespace App\Http\Controllers;

use App\Models\Alias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $aliases = Alias::all();
        return view('dashboard.student', compact('aliases'));
    }
    public function markAbsence($aliasId)
    {
        $student = Auth::guard('student')->user();
        $alias = Alias::findOrFail($aliasId);

        // Rimuovi lo studente dalla relazione N-N con l'alias
        $alias->students()->detach($student->id);

        // Incrementa il contatore delle assenze dello studente
        $student->increment('Nrecoveries');

        // Aggiorna l'array degli studenti nell'alias
        $studenti = $alias->studenti_id ?? [];
        if (($key = array_search($student->id, $studenti)) !== false) {
            unset($studenti[$key]);
        }

        $alias->studenti_id = array_values($studenti); // Rimuove eventuali buchi nell'array
        $alias->save();

        return redirect()->back()->with('success', 'Assenza segnata con successo.');
    }

    public function recAbsence($aliasId)
    {
        $student = Auth::guard('student')->user();
        $alias = Alias::findOrFail($aliasId);

        // Rimuovi lo studente dalla relazione N-N con l'alias
        $alias->students()->attach($student->id);

        // Incrementa il contatore delle assenze dello studente
        $student->decrement('Nrecoveries');

        // Aggiorna l'array degli studenti nell'alias
        $studenti = $alias->studenti_id ?? [];
        if (!in_array($student->id, $studenti)) {
            $studenti[] = "$student->id";
        }

        $alias->studenti_id = array_values($studenti); // Rimuove eventuali buchi nell'array
        $alias->save();

        return redirect()->back()->with('success', 'Recupero segnato con successo.');
    }
}

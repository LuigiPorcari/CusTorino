<?php

namespace App\Http\Controllers;

use App\Models\Alias;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        // $aliases = Alias::all();
        // Recupera gli alias recuperabili
        $recoverableAliases = $this->getRecoverableAliases($student);
        return view('dashboard.student', compact('recoverableAliases'));
    }

    private function getRecoverableAliases($student)
    {
        $allAliases = Alias::all();
        $recoverableAliases = [];

        foreach ($allAliases as $alias) {
            $groups = Group::where('nome', $alias->nome)->get();
            foreach ($groups as $group) {
                if (
                    !in_array($student->id, $group->studenti_id) &&
                    !in_array($student->id, $alias->studenti_id) &&
                    $student->level - 1 <= $alias->livello &&
                    $alias->livello <= $student->level + 2 &&
                    $student->gender == $alias->tipo &&
                    count($alias->studenti_id) < $alias->numero_massimo_partecipanti
                ) {
                    $recoverableAliases[] = $alias;
                }
            }
        }

        return $recoverableAliases;
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

        // Aggiungi lo studente dalla relazione N-N con l'alias
        $alias->students()->attach($student->id);

        // Decrementa il contatore delle assenze dello studente
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

    public function update(Request $request, Student $student)
    {
        $student = Auth::guard('student')->user();
        $student->update([
            'documentation' => $request->documentation,
        ]);

        return redirect()->back()->with('succes', 'Documentazione aggiunta');
    }
}

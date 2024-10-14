<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Alias;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AliasController extends Controller
{
    //!Funzione per segnare assenza
    public function studentAbsence(Request $request, Alias $alias)
    {
        // Validazione della richiesta
        $request->validate([
            'studenti_ids' => 'nullable|array',
            'studenti_ids.*' => 'integer|exists:users,id',
        ]);

        // Ottieni l'array degli ID degli studenti assenti selezionati nel form
        $studentIds = $request->studenti_ids ?? [];

        // Recupera l'array degli ID di tutti gli studenti del gruppo associati all'alias
        $allStudents = $alias->group->users->pluck('id')->toArray();

        // Ottieni gli studenti attualmente segnati come recuperi
        $recoveries = $alias->studenti_id ?? [];

        // Gli studenti presenti sono quelli che non sono stati selezionati come assenti, ma includono i recuperi
        $presentStudents = array_diff($allStudents, $studentIds);

        // Gli studenti presenti non devono essere rimossi dai recuperi, quindi uniamo i presenti con i recuperi
        $updatedStudents = array_unique(array_merge($presentStudents, $recoveries));

        // Rimuovi gli studenti assenti dai recuperi (se presenti nei recuperi)
        $updatedStudents = array_diff($updatedStudents, $studentIds);

        // Aggiorna l'alias con la lista aggiornata di studenti (presenti e recuperi, escludendo gli assenti)
        $alias->studenti_id = array_values($updatedStudents);
        $alias->save();

        // Sincronizza la relazione N-N tra alias e utenti
        $alias->users()->sync($alias->studenti_id);

        return redirect()->back()->with('success', 'Assenze segnate con successo.');
    }





    //!Funzione per segnare i recuperi
    public function recoveriesStudent(Request $request, Alias $alias)
    {
        // Definire le regole di base
        $validator = Validator::make($request->all(), [
            'studenti_id' => 'nullable|array',
            'studenti_id.*' => 'required|integer|exists:users,id',
        ]);

        // Aggiungere una regola di validazione personalizzata per il numero massimo di partecipanti
        $validator->after(function ($validator) use ($alias) {
            $studenti = $validator->getData()['studenti_id'] ?? [];
            $numeroTot = count($studenti) + count($alias->studenti_id);
            if ($numeroTot > $alias->numero_massimo_partecipanti) {
                $validator->errors()->add('studenti_id', 'Il numero massimo di partecipanti è stato superato.');
            }
        });


        // Eseguire la validazione
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $studentIds = $request->studenti_id ?? [];

        // Recupera l'array studenti_id dal gruppo alias
        $currentStudentIds = $alias->studenti_id ?? [];

        foreach ($studentIds as $studentId) {
            if (!in_array($studentId, $currentStudentIds)) {
                $currentStudentIds[] = $studentId;
            }
        }

        // Aggiorna l'array studenti_id del gruppo alias
        $alias->studenti_id = $currentStudentIds;
        $alias->save();

        // Aggiungi gli studenti selezionati alla relazione N-N
        $alias->users()->syncWithoutDetaching($studentIds);

        return redirect()->route('alias.details', compact('alias'))->with('success', 'Recuperi registrati con successo.');
    }
    //!Funzione per modificare i trainer di un alias
    public function aliasUpdate(Request $request, $aliasId)
    {
        $alias = Alias::findOrFail($aliasId);
        $alias->update([
            'primo_allenatore_id' => $request->primo_allenatore_id,
            'secondo_allenatore_id' => $request->secondo_allenatore_id,
            'condiviso' => $request->condiviso,
        ]);
        return redirect()->back()->with('success', 'Presenze allenatori modificati con successo.');
    }

    public function showDetails(Alias $alias)
    {
        $threeDaysCheck = 1;
        // Controlla se la data dell'alias è antecedente a più di tre giorni dalla data odierna
        if (Carbon::parse($alias->data_allenamento)->lt(Carbon::now()->subDays(3))) {
            $threeDaysCheck = 0;
        }
        $trainers = User::where('is_trainer', 1)->get();
        $group = Group::find($alias->group_id);
        $students = $group->users;
        return view('alias.details', compact('alias', 'trainers', 'students', 'threeDaysCheck'));
    }

    public function editStudent(Alias $alias)
    {
        return view('alias.studentEdit', compact('alias'));
    }
}

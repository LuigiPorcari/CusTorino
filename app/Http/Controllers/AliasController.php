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

        $request->validate([
            'studenti_ids' => 'required|array',
            'studenti_ids.*' => 'required|integer|exists:users,id',
        ]);

        $studentIds = $request->studenti_ids;

        // Rimuovi gli studenti selezionati dall'array studenti_id
        $studenti = $alias->studenti_id ?? [];
        foreach ($studentIds as $studentId) {
            if (($key = array_search($studentId, $studenti)) !== false) {
                unset($studenti[$key]);
            }
        }
        $alias->studenti_id = array_values($studenti); // Reindex the array
        $alias->save();
        // Rimuovi la relazione N-N
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
        return view('alias.details', compact('alias', 'trainers', 'students' , 'threeDaysCheck'));
    }

    public function editStudent(Alias $alias)
    {
        return view('alias.studentEdit', compact('alias'));
    }
}

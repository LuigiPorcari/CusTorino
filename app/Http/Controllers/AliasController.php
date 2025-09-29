<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\User;
use App\Models\Alias;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Event;
use App\Traits\LogsActivity;

class AliasController extends Controller
{
    use LogsActivity;
    //!Funzione per segnare assenza
    public function studentAbsence($absentStudentIds, Alias $alias)
    {
        // Ottieni l'array degli ID degli studenti assenti selezionati nel form
        $studentIds = $absentStudentIds ?? [];
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
        return true;
    }
    //!Funzione per segnare i recuperi
    public function recoveriesStudent($studentIds, Alias $alias)
    {
        $studentIds = $studentIds ?? [];
        //   Recupera l'array studenti_id dal gruppo alias
        $currentStudentIds = $alias->studenti_id ?? [];
        foreach ($studentIds as $studentId) {
            if (!in_array($studentId, $currentStudentIds)) {
                $currentStudentIds[] = $studentId;
            }
        }
        //   Aggiorna l'array studenti_id del gruppo alias
        $alias->studenti_id = $currentStudentIds;
        $alias->save();
        //   Aggiungi gli studenti selezionati alla relazione N-N
        $alias->users()->syncWithoutDetaching($studentIds);
        return true;
    }
    //!Funzione per modificare i trainer di un alias
    public function aliasUpdate($trainerData, Alias $alias)
    {
        $alias->update([
            'primo_allenatore_id' => $trainerData['primo_allenatore_id'],
            'secondo_allenatore_id' => $trainerData['secondo_allenatore_id'] ?? null,
            'condiviso' => $trainerData['condiviso'],
        ]);
        return true;
    }
    public function updateAll(Request $request, Alias $alias)
    {
        // Crea il validatore per i dati in `updateAll`
        $validator = Validator::make($request->all(), [
            'all_students.*' => 'integer|exists:users,id',
            'student_absences.*' => 'integer|exists:users,id',
            'student_recoveries.*' => 'integer|exists:users,id',
            'primo_allenatore_id' => 'nullable|integer|exists:users,id',
            'secondo_allenatore_id' => 'nullable|integer|exists:users,id',
            'condiviso' => 'required|in:true,false',
        ]);
        $validator->after(function ($validator) use ($alias, $request) {
            // Ottieni gli ID di tutti gli studenti e quelli selezionati come assenti e recuperi
            $allStudents = $request->input('all_students', []);
            $absentStudents = $request->input('student_absences', []); // Se vuoto, nessuno è assente
            $recoveryStudents = $request->input('student_recoveries', []);
            // Studenti presenti finali: tutti gli studenti meno quelli selezionati come assenti
            $remainingStudents = array_diff($allStudents, $absentStudents);
            // Numero totale dei partecipanti: studenti presenti + recuperi
            $newTotalParticipants = count($remainingStudents) + count($recoveryStudents);
            // Verifica se il nuovo totale supera il numero massimo di partecipanti
            if ($newTotalParticipants > $alias->numero_massimo_partecipanti) {
                $validator->errors()->add('student_recoveries', 'Il numero massimo di partecipanti è stato superato.');
            }
        });
        // Se la validazione fallisce, reindirizza con errori
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Procedi con l'aggiornamento solo se la validazione è passata
        $this->studentAbsence($request->input('student_absences', []), $alias);
        $this->recoveriesStudent($request->input('student_recoveries', []), $alias);
        $this->aliasUpdate([
            'primo_allenatore_id' => $request->input('primo_allenatore_id'),
            'secondo_allenatore_id' => $request->input('secondo_allenatore_id'),
            'condiviso' => $request->input('condiviso'),
        ], $alias);
        return redirect()->back()->with('success', 'Modifiche confermate con successo!');
    }

    // public function checkConf(Alias $alias)
// {
//     // Aggiorna lo stato della conferma
//     $alias->update([
//         'check_conf' => 1,
//     ]);
//     self::logCustomAction('custom_action_name', $alias, null, 'Elemento confermato');
//     return redirect()->back()->with('success', 'Assenze confermate con successo!');
// }

    public function checkConf(Alias $alias)
    {
        // Disabilita temporaneamente gli eventi del modello
        Alias::withoutEvents(function () use ($alias) {
            $alias->update([
                'check_conf' => 1,
            ]);
        });

        // Genera manualmente il log solo per 'Elemento confermato'
        self::logCustomAction('custom_action_name', $alias, null, 'Elemento confermato');

        return redirect()->back()->with('success', 'Assenze confermate con successo!');
    }


    public function showDetails(Alias $alias)
    {
        $threeDaysCheck = 1;
        // Controlla se la data dell'alias è antecedente a più di tre giorni dalla data odierna
        if (Carbon::parse($alias->data_allenamento)->lt(Carbon::now()->subDays(3))) {
            $threeDaysCheck = 0;
        }
        $trainers = User::where('is_trainer', 1)
            ->orderBy('cognome', 'asc')
            ->get();
        $group = Group::find($alias->group_id);
        $students = $group->users;
        return view('alias.details', compact('alias', 'trainers', 'students', 'threeDaysCheck'));
    }
}

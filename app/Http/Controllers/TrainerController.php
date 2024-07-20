<?php

namespace App\Http\Controllers;

use App\Models\Alias;
use App\Models\Student;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrainerController extends Controller
{
    public function dashboard()
    {
        $allenatore_id = Auth::guard('trainer')->user()->id;
        $aliasesPrimoAllenatoreCond = Alias::where('primo_allenatore_id', $allenatore_id)->get();
        $aliasesSecondoAllenatoreCond = Alias::where('secondo_allenatore_id', $allenatore_id)->get();
        $aliasesPrimoAllenatore = [];
        $aliasesSecondoAllenatore = [];
        $aliasesCond = [];
        foreach ($aliasesPrimoAllenatoreCond as $alias) {
            if ($alias->condiviso == "false") {
                $aliasesPrimoAllenatore[] = $alias;
            }
        }
        foreach ($aliasesSecondoAllenatoreCond as $alias) {
            if ($alias->condiviso == "false") {
                $aliasesSecondoAllenatore[] = $alias;
            }
        }
        foreach ($aliasesPrimoAllenatoreCond as $alias) {
            if ($alias->condiviso == "true") {
                $aliasesCond[] = $alias;
            }
        }
        foreach ($aliasesSecondoAllenatoreCond as $alias) {
            if ($alias->condiviso == "true") {
                $aliasesCond[] = $alias;
            }
        }
        $aliasesTrainer = array_merge($aliasesPrimoAllenatore, $aliasesSecondoAllenatore, $aliasesCond);
        $students = Student::all();
        $trainers = Trainer::all();
        return view('dashboard.trainer', compact('aliasesPrimoAllenatore', 'aliasesSecondoAllenatore', 'students', 'trainers' , 'aliasesCond' , 'aliasesTrainer'));
    }

    public function studentAbsence(Request $request, Alias $alias)
    {

        $request->validate([
            'studenti_ids' => 'required|array',
            'studenti_ids.*' => 'required|integer|exists:students,id',
        ]);

        $studentIds = $request->studenti_ids;

        // Rimuovi gli studenti selezionati dall'array studenti_id
        $studenti = $alias->studenti_id ?? [];
        foreach ($studentIds as $studentId) {
            if (($key = array_search($studentId, $studenti)) !== false) {
                unset($studenti[$key]);
                // Incrementa Nrecoveries dello studente
                $student = Student::find($studentId);
                $student->increment('Nrecoveries');
            }
        }
        $alias->studenti_id = array_values($studenti); // Reindex the array
        $alias->save();
        // Rimuovi la relazione N-N
        $alias->students()->sync($alias->studenti_id);

        return redirect()->back()->with('success', 'Assenze segnate con successo.');
    }

    public function editStudent(Alias $alias)
    {
        return view('student.studentEditTrainer', compact('alias'));
    }

    public function recoveriesStudent(Request $request, Alias $alias)
{
    // Definire le regole di base

    $validator = Validator::make($request->all(), [
        'studenti_id' => 'nullable|array',
        'studenti_id.*' => 'required|integer|exists:students,id',
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
            // Decrementa Nrecoveries dello studente
            $student = Student::find($studentId);
            $student->decrement('Nrecoveries');
        }
    }

    // Aggiorna l'array studenti_id del gruppo alias
    $alias->studenti_id = $currentStudentIds;
    $alias->save();

    // Aggiungi gli studenti selezionati alla relazione N-N
    $alias->students()->syncWithoutDetaching($studentIds);

    return redirect()->route('trainer.dashboard')->with('success', 'Recuperi registrati con successo.');
}

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
}

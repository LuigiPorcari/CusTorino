<?php

namespace App\Http\Controllers;

use App\Models\Alias;
use App\Models\Student;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerController extends Controller
{
    public function dashboard()
    {
        $allenatore_id = Auth::guard('trainer')->user()->id;
        $aliasesPrimoAllenatore = Alias::where('primo_allenatore_id', $allenatore_id)->get();
        $aliasesSecondoAllenatore = Alias::where('secondo_allenatore_id', $allenatore_id)->get();
        $students = Student::all();
        $trainers = Trainer::all();
        return view('dashboard.trainer', compact('aliasesPrimoAllenatore', 'aliasesSecondoAllenatore', 'students', 'trainers'));
    }

    public function studentAbsence(Request $request, $aliasId)
    {

        $request->validate([
            'studenti_ids' => 'required|array',
            'studenti_ids.*' => 'required|integer|exists:students,id',
        ]);

        $alias = Alias::findOrFail($aliasId);
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

    public function recoveriesStudent(Request $request, $aliasId)
    {
        $request->validate([
            'studenti_ids' => 'required|array',
            'studenti_ids.*' => 'required|integer|exists:students,id',
        ]);

        $alias = Alias::findOrFail($aliasId);
        $studentIds = $request->studenti_ids;

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
        $alias->students()->sync($alias->studenti_id);
        return redirect()->back()->with('success', 'Recuperi registrati con successo.');
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

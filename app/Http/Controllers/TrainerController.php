<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alias;
use App\Models\Group;
use App\Models\Student;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrainerController extends Controller
{
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

    public function editStudent(Alias $alias)
    {
        return view('student.studentEditTrainer', compact('alias'));
    }

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

        return redirect()->route('trainer.details', compact('alias'))->with('success', 'Recuperi registrati con successo.');
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

    public function dashboard(Request $request)
    {
        $allenatore_id = Auth::user()->id;

        // Calcola la data odierna meno 15 giorni
        $dateThreshold = \Carbon\Carbon::now()->subDays(15)->toDateString();

        // Ottieni le date di allenamento uniche che non sono inferiori alla data odierna meno 15 giorni
        $dates = Alias::where(function ($query) use ($allenatore_id) {
            $query->where('primo_allenatore_id', $allenatore_id)
                ->orWhere('secondo_allenatore_id', $allenatore_id);
        })
            ->whereDate('data_allenamento', '>=', $dateThreshold) // Filtro per data
            ->distinct('data_allenamento')
            ->pluck('data_allenamento')
            ->sortBy(function ($date) {
                return \Carbon\Carbon::parse($date);
            });

        // Instanzia un oggetto Alias per utilizzare i metodi di formattazione
        $alias = new Alias();

        // Formatta le date
        $availableDates = $dates->map(function ($date) use ($alias) {
            return [
                'formatted' => $alias->formatData($date),
                'original' => $date
            ];
        });

        // Filtro per nome e data
        $query = Alias::where(function ($query) use ($allenatore_id) {
            $query->where('primo_allenatore_id', $allenatore_id)
                ->orWhere('secondo_allenatore_id', $allenatore_id);
        })
            ->whereDate('data_allenamento', '>=', $dateThreshold); // Filtro per data

        if ($request->filled('alias_name')) {
            $query->where('nome', 'like', '%' . $request->alias_name . '%');
        }

        if ($request->filled('alias_date')) {
            $query->whereDate('data_allenamento', $request->alias_date);
        }

        $aliasesTrainer = $query->orderBy('data_allenamento', 'asc')->paginate(9);

        return view('dashboard.trainer', compact('aliasesTrainer', 'availableDates'));
    }


    public function showDetails(Alias $alias)
    {
        $trainers = User::where('is_trainer', 1)->get();
        $group = Group::find($alias->group_id);
        $students = $group->users;
        return view('trainer.details', compact('alias', 'trainers', 'students'));
    }
}

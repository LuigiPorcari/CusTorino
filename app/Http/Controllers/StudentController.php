<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alias;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard(Request $request)
    {
        $student = Auth::user();

        // Recupera le date disponibili per gli alias dove lo studente è presente
        $availableTrainingDates = $student->aliases()
            ->select('data_allenamento')
            ->distinct()
            ->orderBy('data_allenamento')
            ->get()
            ->map(function ($alias) {
                return [
                    'raw' => $alias->data_allenamento,
                    'formatted' => $alias->formatData($alias->data_allenamento)
                ];
            })
            ->unique('raw')
            ->values();

        $trainingDate = $request->input('training_date');

        $trainingAliasesQuery = $student->aliases();
        if ($trainingDate) {
            $trainingAliasesQuery->whereDate('data_allenamento', $trainingDate);
        }
        $trainingAliases = $trainingAliasesQuery->get()->sortBy('data_allenamento');

        $recoverableAliases = $this->getRecoverableAliases($student);

        // Ordinare $recoverableAliases per data crescente
        $recoverableAliases = collect($recoverableAliases)->sortBy('data_allenamento');

        return view('dashboard.student', compact('trainingAliases', 'recoverableAliases', 'availableTrainingDates'));
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
                    $student->livello - 1 <= $alias->livello &&
                    $alias->livello <= $student->livello + 2 &&
                    $student->genere == $alias->tipo &&
                    count($alias->studenti_id) < $alias->numero_massimo_partecipanti
                ) {
                    $recoverableAliases[] = $alias;
                }
            }
        }
        // Converti l'array in una Collection e ordina per data crescente
        return collect($recoverableAliases)->sortBy('data_allenamento');
    }

    public function markAbsence($aliasId)
    {
        $student = Auth::user();
        $alias = Alias::findOrFail($aliasId);
        // Ottieni la data e l'orario dell'alias
        $aliasDateTime = \Carbon\Carbon::parse($alias->data_allenamento . ' ' . $alias->orario);
        // Ottieni l'orario attuale
        $now = \Carbon\Carbon::now();
        // Calcola la data e l'orario limite per l'incremento (12 ore prima dell'alias)
        $deadline = $aliasDateTime->copy()->subHours(12);
        // Verifica se l'orario attuale è prima della scadenza
        if ($now->lte($deadline) && $student->cus_card && $student->visita_medica && $student->pagamento) {
            // Incrementa il contatore delle assenze dello studente
            $student->increment('NrecuperiTemp');
        }
        // Rimuovi lo studente dalla relazione N-N con l'alias
        $alias->users()->detach($student->id);
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
        $student = Auth::user();
        $alias = Alias::findOrFail($aliasId);

        // Aggiungi lo studente dalla relazione N-N con l'alias
        $alias->users()->attach($student->id);

        // Decrementa il contatore delle assenze dello studente
        $student->decrement('Nrecuperi');

        // Aggiorna l'array degli studenti nell'alias
        $studenti = $alias->studenti_id ?? [];
        if (!in_array($student->id, $studenti)) {
            $studenti[] = "$student->id";
        }

        $alias->studenti_id = array_values($studenti); // Rimuove eventuali buchi nell'array
        $alias->save();

        return redirect()->back()->with('success', 'Recupero segnato con successo.');
    }

    public function update(Request $request, User $student)
    {
        $student = Auth::user();
        $student->update([
            'documentation' => $request->documentation,
        ]);

        return redirect()->back()->with('succes', 'Documentazione aggiunta');
    }
}

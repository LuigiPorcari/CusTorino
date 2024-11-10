<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Alias;
use App\Models\Group;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StudentController extends Controller
{
    use LogsActivity;
    public function dashboard(Request $request)
    {
        $student = Auth::user();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow(); // Data di domani
        $twoWeeksLater = Carbon::today()->addWeeks(2); // Data limite tra due settimane

        // Recupera le date disponibili per gli alias dove lo studente è presente (assenze)
        $availableTrainingDates = $student->aliases()
            ->select('data_allenamento')
            ->whereDate('data_allenamento', '>=', $today) // Escludi la data odierna (solo future)
            ->distinct()
            ->orderBy('data_allenamento', 'asc') // Ordina per data di allenamento
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

        // Query per gli alias di allenamento filtrati per data e ordinati per data_allenamento
        $trainingAliasesQuery = $student->aliases()->whereDate('data_allenamento', '>=', $today); // Escludi la data odierna

        if ($trainingDate) {
            $trainingAliasesQuery->whereDate('data_allenamento', $trainingDate);
        }

        // Ordina gli alias per data di allenamento
        $trainingAliases = $trainingAliasesQuery->orderBy('data_allenamento', 'asc')->paginate(4); // Mostra 4 risultati per pagina

        // Recupera gli alias recuperabili entro 2 settimane, escludendo la data odierna
        $recoverableAliases = $this->getRecoverableAliases($student)
            ->filter(function ($alias) use ($tomorrow, $twoWeeksLater) {
                return Carbon::parse($alias->data_allenamento)->between($tomorrow, $twoWeeksLater);
            });

        // Estrai le date valide per il recupero e ordinali per data di allenamento
        $availableRecoveryDates = $recoverableAliases->pluck('data_allenamento')
            ->unique()
            ->map(function ($date) {
                return [
                    'raw' => $date,
                    'formatted' => Alias::where('data_allenamento', $date)->first()->formatData($date)
                ];
            })
            ->unique('raw')
            ->values();

        $recoveryDate = $request->input('recovery_date');

        // Filtro per data di recupero
        if ($recoveryDate) {
            $recoverableAliases = $recoverableAliases->filter(function ($alias) use ($recoveryDate) {
                return $alias->data_allenamento == $recoveryDate;
            });
        }

        // Ordina per data_allenamento in modo crescente
        $recoverableAliases = $recoverableAliases->sortBy('data_allenamento');

        // Restituisce la vista con gli alias ordinati
        return view('dashboard.student', compact('trainingAliases', 'recoverableAliases', 'availableTrainingDates', 'availableRecoveryDates'));
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
                    $alias->livello <= $student->livello + 1 &&
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

        // Logica per segnare l'assenza
        $aliasDateTime = Carbon::parse($alias->data_allenamento . ' ' . $alias->orario)->addHour();
        $now = Carbon::now()->addHour();
        $deadline = $aliasDateTime->copy()->subHours(12);

        if ($now->lte($deadline) && $student->cus_card && $student->visita_medica && $student->pagamento) {
            $student->increment('Nrecuperi');
        }

        $alias->users()->detach($student->id);

        $studenti = $alias->studenti_id ?? [];
        if (($key = array_search($student->id, $studenti)) !== false) {
            unset($studenti[$key]);
        }
        $alias->studenti_id = array_values($studenti);
        $alias->save();

        // Salva l'ultima azione nel database
        $student->last_action = 'mark_absence';
        $student->last_alias_id = $alias->id;
        $student->save();  // Assicurati che il salvataggio venga effettuato

        self::logCustomAction('custom_action_name', $alias, null, 'Assenza');
        return redirect()->back()->with('success', 'Assenza segnata con successo.');
    }

    public function recAbsence($aliasId)
    {
        $student = Auth::user();
        $alias = Alias::findOrFail($aliasId);

        // Logica per segnare il recupero
        $alias->users()->attach($student->id);
        $student->decrement('Nrecuperi');

        $studenti = $alias->studenti_id ?? [];
        if (!in_array($student->id, $studenti)) {
            $studenti[] = "$student->id";
        }
        $alias->studenti_id = array_values($studenti);
        $alias->save();

        // Salva l'ultima azione nel database
        $student->last_action = 'rec_absence';
        $student->last_alias_id = $alias->id;
        $student->save();  // Assicurati che il salvataggio venga effettuato

        self::logCustomAction('custom_action_name', $alias, null, 'Recupero');
        return redirect()->back()->with('success1', 'Recupero segnato con successo.');
    }


    public function undoLastAction()
    {
        $student = Auth::user();

        if (!$student->last_action || !$student->last_alias_id) {
            return redirect()->back()->with('error', 'Nessuna operazione da annullare.');
        }

        $alias = Alias::findOrFail($student->last_alias_id);

        // Recuperiamo l'array studenti_id dell'alias
        $studenti = $alias->studenti_id ?? [];

        if ($student->last_action == 'mark_absence') {
            // Annulla la segnatura di un'assenza:
            if ($student->cus_card && $student->visita_medica && $student->pagamento) {
                $student->decrement('Nrecuperi');
            }
            $alias->users()->attach($student->id); // Riassegna lo studente all'alias

            // Aggiungi lo studente all'array studenti_id se non è già presente
            if (!in_array((string) $student->id, $studenti)) {
                $studenti[] = (string) $student->id; // Aggiungi l'ID come stringa
            }

        } elseif ($student->last_action == 'rec_absence') {
            // Annulla un recupero:
            $student->increment('Nrecuperi');
            $alias->users()->detach($student->id); // Rimuovi lo studente dall'alias

            // Rimuovi lo studente dall'array studenti_id
            if (($key = array_search((string) $student->id, $studenti)) !== false) {
                unset($studenti[$key]);
            }
        }

        // Aggiorna l'array studenti_id dell'alias e rimuovi eventuali buchi
        $alias->studenti_id = array_values($studenti);
        $alias->save();

        // Pulisci l'ultima azione
        $student->last_action = null;
        $student->last_alias_id = null;
        $student->save();

        self::logCustomAction('custom_action_name', $alias, null, 'Annulamento operazione');
        // Ritorna alla pagina precedente con successo
        return redirect()->back()->with('success', 'Ultima operazione annullata con successo.');
    }




}

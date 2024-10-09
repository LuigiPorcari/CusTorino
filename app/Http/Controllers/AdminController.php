<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Alias;
use App\Models\Group;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Gruppi: Filtro per nome
        $groupQuery = Group::query();
        if ($request->filled('group_name')) {
            $groupQuery->where('nome', 'like', '%' . $request->group_name . '%');
        }
        $groups = $groupQuery->paginate(50);
        return view('dashboard.admin', compact('groups'));
    }

    public function groupDetails(Group $group, Request $request)
    {
        // Filtro per data
        $aliasQuery = $group->aliases()->orderBy('data_allenamento', 'asc');

        if ($request->filled('data_allenamento')) {
            $aliasQuery->whereDate('data_allenamento', $request->data_allenamento);
        }

        $aliases = $aliasQuery->get();

        // Ottieni tutte le date uniche degli alias per il filtro
        $availableDates = $group->aliases()
            ->select('data_allenamento')
            ->orderBy('data_allenamento', 'asc')
            ->distinct()
            ->get();


        $dateMancanti = $this->getMissingAliasDates($group);

        return view('dashboard.adminGroupDetails', compact('group', 'aliases', 'availableDates', 'dateMancanti'));
    }

    public function dashboardTrainer(Request $request)
    {
        // Recupera tutti i trainer e i gruppi per la vista
        $trainers = User::where('is_trainer', 1)->paginate(10);
        $groups = Group::all(); // Recupera tutti i gruppi per il filtro dei gruppi

        return view('dashboard.adminTrainer', compact('trainers', 'groups'));
    }

    public function dashboardStudent(Request $request)
    {

        $groups = Group::all();
        $studentQuery = User::where('is_corsista', 1);
        $students = $studentQuery->paginate(10);

        return view('dashboard.adminStudent', compact('students', 'groups'));
    }

    public function trainerDetails(User $trainer)
    {
        // Recupera gli alias dove il trainer è il primo o secondo allenatore, ordinati per data_allenamento
        $aliasesTrainer = Alias::where(function ($query) use ($trainer) {
            $query->where('primo_allenatore_id', $trainer->id)
                ->orWhere('secondo_allenatore_id', $trainer->id);
        })
            ->orderBy('data_allenamento', 'asc') // Ordina per data_allenamento in ordine crescente
            ->get();

        return view('dashboard.adminTrainerDetails', compact('trainer', 'aliasesTrainer'));
    }

    public function updateStudent(Request $request, User $student)
    {
        //Modifica Corsista
        $student->update([
            'livello' => $request->level,
            'cus_card' => $request->cus_card,
            'visita_medica' => $request->visita_medica,
            'pagamento' => $request->pagamento,
            'universitario' => $request->universitario,
            'is_trainer' => $request->is_trainer,
            'Nrecuperi' => $request->Nrecuperi,
            'genere' => $request->genere,
            'name' => $request->name,
            'cognome' => $request->cognome
        ]);

        return redirect(route('admin.dashboard.student'))->with('success', 'Corsista modificato con successo');
    }

    public function makeTrainerAndStudent(Request $request, User $trainer)
    {
        $trainer->update([
            'is_corsista' => $request->is_corsista
        ]);

        return redirect(route('admin.dashboard.trainer'))->with('success', 'Trainer modificato con successo');
    }

    public function studentDetails(User $student)
    {
        return view('dashboard.adminStudentDetails', compact('student'));
    }

    public function getMissingAliasDates(Group $group)
    {
        // Ottieni le date di inizio e fine del corso
        $dataInizio = Carbon::parse($group->data_inizio_corso);
        $dataFine = Carbon::parse($group->data_fine_corso);

        // Ottieni tutte le date già esistenti per questo gruppo alias
        $dateEsistenti = $group->aliases()->pluck('data_allenamento')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d'); // Formatta le date esistenti come stringhe
        })->toArray();

        // Genera tutte le date tra data_inizio_corso e data_fine_corso
        $possibiliDate = [];
        $currentDate = $dataInizio->copy();

        // Aggiungi tutte le date settimanali a partire dalla data di inizio fino alla data di fine
        while ($currentDate->lte($dataFine)) {
            $possibiliDate[] = $currentDate->copy()->format('Y-m-d'); // Salva ogni data in formato stringa
            $currentDate->addWeek(); // Aggiungi una settimana
        }

        // Filtra le date mancanti: prendi le date possibili che non sono presenti tra quelle esistenti
        $dateMancanti = array_diff($possibiliDate, $dateEsistenti);

        return array_values($dateMancanti); // Restituisci l'array delle date mancanti
    }

    public function storeAlias(Request $request, Group $group)
    {
        // Validazione della data di allenamento
        $request->validate([
            'data_allenamento' => 'required|date',
        ]);

        // Controlla se la data è già presente per evitare duplicati
        $existingAlias = $group->aliases()->where('data_allenamento', $request->data_allenamento)->first();

        if ($existingAlias) {
            return redirect()->back()->with('error', 'Il gruppo alias esiste già per questa data.');
        }

        // Creazione del nuovo alias basato sulle proprietà del gruppo originale
        $alias = Alias::create([
            'nome' => $group->nome,
            'group_id' => $group->id,
            'data_allenamento' => $request->data_allenamento,
            'orario' => $group->orario,
            'campo' => $group->campo,
            'tipo' => $group->tipo,
            'primo_allenatore_id' => $group->primo_allenatore_id,
            'secondo_allenatore_id' => $group->secondo_allenatore_id,
            'condiviso' => $group->condiviso,
            'numero_massimo_partecipanti' => $group->numero_massimo_partecipanti,
            'livello' => $group->livello,
            'location' => $group->location,
            'studenti_id' => $group->studenti_id,
        ]);


        $alias->users()->sync($group->studenti_id);
        $alias->save();

        return redirect()->route('admin.group.details', $group->id)->with('success', 'Gruppo Alias creato con successo');
    }

}

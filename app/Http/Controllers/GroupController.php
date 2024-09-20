<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Alias;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function create()
    {
        $trainers = User::where('is_trainer', 1)->get();
        $students = User::where('is_corsista', 1)->get();
        return view('groups.create', compact('trainers', 'students'));
    }

    public function editStudent(Group $group)
    {
        // FILTRO STUDENTI IMPLEMENTABILE
        $students = User::where('is_corsista', 1)->get();
        $studentsAvaiable = [];
        foreach ($students as $student) {
            if ($student->genere == $group->tipo) {
                $studentsAvaiable[] = $student;
            }
        }
        return view('groups.createStudent', compact('group', 'studentsAvaiable'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'giorno_settimana' => 'required|string',
            'orario' => 'required',
            'campo' => 'required|integer|min:1|max:4',
            'tipo' => 'required|string',
            'primo_allenatore_id' => 'required|integer|exists:users,id',
            'secondo_allenatore_id' => 'nullable|integer|exists:users,id',
            'condiviso' => 'required|string',
            'numero_massimo_partecipanti' => 'required|integer',
            'livello' => 'required|integer|min:1|max:10',
            'data_inizio_corso' => 'required|date',
            'data_fine_corso' => 'required|date|after_or_equal:data_inizio_corso',
            'location' => 'required|string',
        ]);

        $group = Group::create([
            'nome' => $request->nome,
            'giorno_settimana' => $request->giorno_settimana,
            'orario' => $request->orario,
            'campo' => $request->campo,
            'tipo' => $request->tipo,
            'primo_allenatore_id' => $request->primo_allenatore_id,
            'secondo_allenatore_id' => $request->secondo_allenatore_id,
            'condiviso' => $request->condiviso,
            'numero_massimo_partecipanti' => $request->numero_massimo_partecipanti,
            'livello' => $request->livello,
            'data_inizio_corso' => $request->data_inizio_corso,
            'data_fine_corso' => $request->data_fine_corso,
            'location' => $request->location,
        ]);

        $group->studenti_id = []; // Imposta l'array vuoto se necessario
        $group->save();

        // Creazione dei gruppi alias
        $dataInizio = Carbon::parse($request->data_inizio_corso);
        $dataFine = Carbon::parse($request->data_fine_corso);
        $giornoSettimana = Carbon::parse($request->data_inizio_corso)->dayOfWeek;

        while ($dataInizio->lte($dataFine)) {
            $alias = Alias::create([
                'nome' => $request->nome,
                'group_id' => $group->id,
                'data_allenamento' => $dataInizio->copy(),
                'orario' => $request->orario,
                'campo' => $request->campo,
                'tipo' => $request->tipo,
                'primo_allenatore_id' => $request->primo_allenatore_id,
                'secondo_allenatore_id' => $request->secondo_allenatore_id,
                'condiviso' => $request->condiviso,
                'numero_massimo_partecipanti' => $request->numero_massimo_partecipanti,
                'livello' => $request->livello,
                'location' => $request->location,
            ]);
            $dataInizio->addWeek();
            $alias->studenti_id = []; // Imposta l'array vuoto se necessario
            $alias->save();
        }
        return redirect()->route('admin.dashboard')->with('success', 'Gruppo creato con successo');
    }

    public function edit(Group $group)
    {
        $trainers = User::where('is_trainer', 1)->get();
        $students = User::where('is_corsista', 1)->get();
        return view('groups.edit', compact('group', 'trainers', 'students'));
    }

    public function update(Request $request, Group $group)
    {
        $group->update([
            'nome' => $request->nome,
            'giorno_settimana' => $request->giorno_settimana,
            'orario' => $request->orario,
            'campo' => $request->campo,
            'tipo' => $request->tipo,
            'primo_allenatore_id' => $request->primo_allenatore_id,
            'secondo_allenatore_id' => $request->secondo_allenatore_id,
            'condiviso' => $request->condiviso,
            'numero_massimo_partecipanti' => $request->numero_massimo_partecipanti,
            'livello' => $request->livello,
            'location' => $request->location,
        ]);

        // Modifica dei gruppi alias
        foreach ($group->aliases as $alias) {
            $alias->update([
                'nome' => $request->nome,
                'group_id' => $group->id,
                'orario' => $request->orario,
                'campo' => $request->campo,
                'tipo' => $request->tipo,
                'primo_allenatore_id' => $request->primo_allenatore_id,
                'secondo_allenatore_id' => $request->secondo_allenatore_id,
                'condiviso' => $request->condiviso,
                'numero_massimo_partecipanti' => $request->numero_massimo_partecipanti,
                'livello' => $request->livello,
                'location' => $request->location,
            ]);
        }
        return redirect(route('admin.group.details', compact('group')))->with('success', 'Gruppo modificato con successo');
    }

    public function createStudent(Request $request, Group $group)
    {
        // Inizializza gli studenti precedenti come array vuoto se non esistono
        $studentOld = $group->studenti_id ?? [];

        // Verifica che $request->studenti_id sia un array valido
        $studentiNuovi = $request->studenti_id ?? [];

        // Trova gli studenti rimossi (presenti prima, ma non più selezionati nel form)
        $studentiRimossi = array_diff($studentOld, $studentiNuovi);

        // Trova gli studenti aggiunti (nuovi nel gruppo)
        $studentAggiunti = array_diff($studentiNuovi, $studentOld);

        // Definire le regole di base
        $validator = Validator::make($request->all(), [
            'studenti_id' => 'nullable|array',
            'studenti_id.*' => 'required|integer|exists:users,id',
        ]);

        // Aggiungere una regola di validazione personalizzata per il numero massimo di partecipanti
        $validator->after(function ($validator) use ($group) {
            $studenti = $validator->getData()['studenti_id'] ?? [];
            if (count($studenti) > $group->numero_massimo_partecipanti) {
                $validator->errors()->add('studenti_id', 'Il numero massimo di partecipanti è stato superato.');
            }
        });

        // Eseguire la validazione
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Aggiorna il gruppo con i nuovi studenti
        $group->update([
            'studenti_id' => $studentiNuovi,
        ]);

        // Sincronizza i nuovi studenti con il gruppo
        $group->users()->sync($studentiNuovi);

        // Modifica degli alias
        foreach ($group->aliases as $alias) {
            // Inizializza gli studenti dell'alias come array vuoto se null
            $studentiAlias = $alias->studenti_id ?? [];

            // Trova gli studenti presenti sia nel vecchio array che nell'alias
            $studentPresenti = array_intersect($studentOld, $studentiAlias);

            // Trova gli studenti extra che sono solo nell'alias e non nel gruppo
            $studentiExtraAlias = array_diff($studentiAlias, $studentOld);

            // Unisci gli studenti presenti con quelli aggiunti e mantieni gli studenti extra
            $corsisti = array_merge($studentPresenti, $studentAggiunti, $studentiExtraAlias);

            // Rimuovi gli studenti deselezionati dal gruppo, ma non gli studenti extra dell'alias
            $corsisti = array_diff($corsisti, $studentiRimossi);

            // Elimina eventuali duplicati e reindicizza l'array
            $corsisti = array_unique($corsisti);
            $corsisti = array_values($corsisti);

            // Aggiorna l'alias con i nuovi studenti
            $alias->update([
                'studenti_id' => $corsisti,
            ]);

            // Sincronizza l'alias con i nuovi studenti
            $alias->users()->sync($corsisti);
        }

        return redirect(route('admin.group.details', ['group' => $group->id]))
            ->with('success', 'Operazione avvenuta con successo');
    }




    public function delete(Group $group)
    {
        $array_vuoto = [];
        foreach ($group->aliases as $alias) {
            $alias->users()->sync($array_vuoto);
        }
        $group->aliases()->delete();
        $group->users()->sync($array_vuoto);
        $group->delete();
        return redirect(route('admin.dashboard'))->with('success', 'Gruppo cancellato');
    }

    public function deleteAlias(Alias $alias)
    {
        $group = $alias->group;  // Recupera il gruppo a cui appartiene l'alias

        // Rimuovi tutti gli utenti associati all'alias
        $alias->users()->sync([]);

        // Elimina l'alias
        $alias->delete();

        // Reindirizza alla pagina dei dettagli del gruppo con il messaggio di successo
        return redirect()->route('admin.group.details', ['group' => $group->id])
            ->with('success', 'Gruppo Alias cancellato');
    }

}

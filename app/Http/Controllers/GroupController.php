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
        $trainers = User::where('is_trainer' , 1)->get();
        $students = User::where('is_corsista' , 1)->get();
        return view('groups.create', compact('trainers', 'students'));
    }

    public function editStudent(Group $group)
    {
        // FILTRO STUDENTI IMPLEMENTABILE
        $students = User::where('is_corsista' , 1)->get();
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
            ]);
            $dataInizio->addWeek();
            $alias->studenti_id = []; // Imposta l'array vuoto se necessario
            $alias->save();
        }
        return redirect()->route('admin.dashboard')->with('success', 'Gruppo creato con successo');
    }

    public function edit(Group $group)
    {
        $trainers = User::where('is_trainer' , 1)->get();
        $students = User::where('is_corsista' , 1)->get();
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
            ]);
        }
        return redirect(route('admin.group.details', compact('group')))->with('success', 'Gruppo modificato con successo');
    }

    public function createStudent(Request $request, Group $group)
    {
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


        $group->update([
            'studenti_id' => $request->studenti_id,
        ]);

        $group->users()->sync($request->input('studenti_id'));

        // Modifica dei gruppi alias
        foreach ($group->aliases as $alias) {
            $alias->update([
                'studenti_id' => $request->studenti_id,
            ]);
            $alias->users()->sync($request->input('studenti_id'));
        }
        return redirect(route('admin.group.details', compact('group')))->with('success', 'Operazione avvenuta con successo');
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
}

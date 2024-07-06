<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Alias;
use App\Models\Trainer;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GroupController extends Controller
{
    public function create()
    {
        $trainers = Trainer::all();
        $students = Student::all();
        return view('groups.create', compact('trainers', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'giorno_settimana' => 'required|string',
            'orario' => 'required',
            'campo' => 'required|integer',
            'tipo' => 'required|string',
            'primo_allenatore_id' => 'required|integer|exists:trainers,id',
            'secondo_allenatore_id' => 'nullable|integer|exists:trainers,id',
            'condiviso' => 'required|string',
            'numero_massimo_partecipanti' => 'required|integer',
            'livello' => 'required|integer|min:1|max:10',
            'studenti_id' => 'nullable|array',
            'studenti_id.*' => 'integer|exists:students,id',
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
            'studenti_id' => $request->studenti_id,
            'data_inizio_corso' => $request->data_inizio_corso,
            'data_fine_corso' => $request->data_fine_corso,
        ]);

        foreach ($request->input('studenti_id') as $student) {
            $group->students()->attach($student);
        }

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
                'studenti_id' => $request->studenti_id,
            ]);

            foreach ($request->input('studenti_id') as $student) {
                $alias->students()->attach($student);
            }

            $dataInizio->addWeek();
        }

        return redirect()->route('groups.create')->with('success', 'Gruppo creato con successo');
    }
}

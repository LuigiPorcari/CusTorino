<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $groups = $groupQuery->paginate(10);
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

        return view('dashboard.adminGroupDetails', compact('group', 'aliases', 'availableDates'));
    }

    public function dashboardTrainer(Request $request)
    {
        // Trainer: Filtro per nome e cognome combinati
        $trainerQuery = User::where('is_trainer', 1);

        if ($request->filled('trainer_name')) {
            $searchTerm = $request->input('trainer_name');
            $trainerQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('cognome', 'like', '%' . $searchTerm . '%')
                    ->orWhereRaw("CONCAT(name, ' ', cognome) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        $trainers = $trainerQuery->paginate(10);

        return view('dashboard.adminTrainer', compact('trainers'));
    }


    public function dashboardStudent(Request $request)
    {
        // Studenti: Filtro per nome e cognome combinati
        $studentQuery = User::where('is_corsista', 1);

        if ($request->filled('student_name')) {
            $searchTerm = $request->input('student_name');
            $studentQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('cognome', 'like', '%' . $searchTerm . '%')
                    ->orWhereRaw("CONCAT(name, ' ', cognome) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        $students = $studentQuery->paginate(10);

        return view('dashboard.adminStudent', compact('students'));
    }


    public function trainerDetails(User $trainer)
    {
        return view('dashboard.adminTrainerDetails', compact('trainer'));
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
}

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

    public function groupDetails(Group $group)
    {
        return view('dashboard.adminGroupDetails', compact('group'));
    }

    public function dashboardTrainer(Request $request)
    {
        // Trainer: Filtro per nome
        $trainerQuery = User::where('is_trainer', 1);
        if ($request->filled('trainer_name')) {
            $trainerQuery->where('name', 'like', '%' . $request->trainer_name . '%')
                ->orWhere('cognome', 'like', '%' . $request->trainer_name . '%');
        }
        $trainers = $trainerQuery->paginate(10);
        return view('dashboard.adminTrainer', compact('trainers'));
    }

    public function dashboardStudent(Request $request)
    {
        // Studenti: Filtro per nome
        $studentQuery = User::where('is_corsista', 1);
        if ($request->filled('student_name')) {
            $studentQuery->where('name', 'like', '%' . $request->student_name . '%')
                ->orWhere('cognome', 'like', '%' . $request->student_name . '%');
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
        //Calocola valore inserito
        $enteredValor = $request->Nrecuperi - $student->Nrecuperi;
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
        // Calcola il nuovo valore di NrecuperiTemp utilizzando il valore aggiornato di Nrecuperi
        $newNrecuperiTemp = $student->NrecuperiTemp - $enteredValor;

        // Aggiorna il valore di NrecuperiTemp nel modello
        $student->update(['NrecuperiTemp' => $newNrecuperiTemp]);

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

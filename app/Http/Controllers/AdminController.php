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
        $trainerQuery = User::where('is_trainer' , 1);
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
        $studentQuery = User::where('is_corsista' , 1);
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
        $student->update([
            'documentation' => $request->documentation,
            'livello' => $request->level,
        ]);

        return redirect(route('admin.dashboard.student'))->with('success', 'Corsista modificato con successo');
    }
}

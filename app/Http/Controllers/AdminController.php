<?php

namespace App\Http\Controllers;

use App\Models\Alias;
use App\Models\Group;
use App\Models\Student;
use App\Models\Trainer;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // public function dashboard()
    // {
    //     $groups = Group::paginate(1);
    //     $aliases = Alias::all();
    //     $trainers = Trainer::paginate(5);
    //     return view('dashboard.admin', compact('groups', 'aliases', 'trainers'));
    // }

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

    public function dashboardTrainer(Request $request)
    {
        // Trainer: Filtro per nome
        $trainerQuery = Trainer::query();
        if ($request->filled('trainer_name')) {
            $trainerQuery->where('nome', 'like', '%' . $request->trainer_name . '%')
                ->orWhere('cognome', 'like', '%' . $request->trainer_name . '%');
        }
        $trainers = $trainerQuery->paginate(10);
        return view('dashboard.adminTrainer', compact('trainers'));
    }

    public function dashboardStudent(Request $request)
    {
        // Studenti: Filtro per nome
        $studentQuery = Student::query();
        if ($request->filled('student_name')) {
            $studentQuery->where('nome', 'like', '%' . $request->student_name . '%')
                ->orWhere('cognome', 'like', '%' . $request->student_name . '%');
        }
        $students = $studentQuery->paginate(10);
        return view('dashboard.adminStudent', compact('students'));
    }

    public function groupDetails(Group $group)
    {
        return view('dashboard.adminGroupDetails', compact('group'));
    }

    public function trainerDetails(Trainer $trainer)
    {
        return view('dashboard.adminTrainerDetails', compact('trainer'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $student->update([
            'documentation' => $request->documentation,
            'level' => $request->level,
        ]);

        return redirect(route('admin.dashboard.student'))->with('success', 'Corsista modificato con successo');
    }
}

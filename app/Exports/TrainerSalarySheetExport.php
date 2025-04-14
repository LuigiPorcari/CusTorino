<?php

namespace App\Exports;

use App\Models\Alias;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TrainerSalarySheetExport implements FromView
{
    public $trainer;

    public function __construct(User $trainer)
    {
        $this->trainer = $trainer;
    }

    public function view(): View
    {
        $aliasesTrainer = Alias::where(function ($query) {
            $query->where('primo_allenatore_id', $this->trainer->id)
                ->orWhere('secondo_allenatore_id', $this->trainer->id);
        })->orderBy('data_allenamento', 'asc')->get();

        $totalSalary = $this->trainer->calcolaStipendioAllenatore($this->trainer->id);
        $totalPresence = $this->trainer->countAttendanceTrainer($this->trainer);
        $totalAbsence = $this->trainer->countAbsenceTrainer($this->trainer);

        return view('exports.trainer_salary_excel', [
            'aliasesTrainer' => $aliasesTrainer,
            'totalSalary' => $totalSalary,
            'totalPresence' => $totalPresence,
            'totalAbsence' => $totalAbsence,
            'trainerName' => $this->trainer->name,
            'trainer' => $this->trainer,
        ]);
    }
}

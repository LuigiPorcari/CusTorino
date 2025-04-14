<?php

namespace App\Exports;

use App\Models\User;
use App\Exports\TrainerSalarySheetExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllTrainersSalaryExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        // Supponiamo che tutti gli allenatori abbiano ruolo 'trainer'
        $trainers = User::where('is_trainer', 1)->get();

        foreach ($trainers as $trainer) {
            $sheets[] = new TrainerSalarySheetExport($trainer);
        }

        return $sheets;
    }
}

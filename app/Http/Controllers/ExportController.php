<?php

namespace App\Http\Controllers;

use App\Exports\DatabaseExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function export()
    {
        // 🔹 Elenco delle tabelle da esportare
        $tables = ['groups', 'users'];

        return Excel::download(new DatabaseExport($tables), 'database_export.xlsx');
    }
}

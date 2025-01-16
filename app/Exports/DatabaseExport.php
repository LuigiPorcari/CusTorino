<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DatabaseExport implements WithMultipleSheets
{
    private $tablesToExport;

    public function __construct(array $tablesToExport)
    {
        $this->tablesToExport = $tablesToExport;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->tablesToExport as $tableName) {
            $sheets[] = new TableExport($tableName);
        }

        return $sheets;
    }
}

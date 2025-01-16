<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TableExport implements FromCollection, WithTitle, WithHeadings
{
    protected $tableName;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public function collection()
    {
        return collect(DB::select("SELECT * FROM `{$this->tableName}`"));
    }

    public function headings(): array
    {
        $columns = DB::select("SHOW COLUMNS FROM `{$this->tableName}`");

        return array_map(function ($column) {
            return $column->Field;
        }, $columns);
    }

    public function title(): string
    {
        return $this->tableName;
    }
}

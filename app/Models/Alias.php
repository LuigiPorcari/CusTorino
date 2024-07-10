<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alias extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'group_id',
        'data_allenamento',
        'orario',
        'campo',
        'tipo',
        'primo_allenatore_id',
        'secondo_allenatore_id',
        'condiviso',
        'numero_massimo_partecipanti',
        'livello',
        'studenti_id',
    ];

    protected $casts = [
        'studenti_id' => 'array',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function primoAllenatore()
    {
        return $this->belongsTo(Trainer::class, 'primo_allenatore_id');
    }

    public function secondoAllenatore()
    {
        return $this->belongsTo(Trainer::class, 'secondo_allenatore_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}

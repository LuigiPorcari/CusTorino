<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'giorno_settimana',
        'orario',
        'campo',
        'tipo',
        'primo_allenatore_id',
        'secondo_allenatore_id',
        'condiviso',
        'numero_massimo_partecipanti',
        'livello',
        'studenti_id',
        'data_inizio_corso',
        'data_fine_corso',
    ];

    protected $casts = [
        'studenti_id' => 'array',
    ];

    public function primoAllenatore()
    {
        return $this->belongsTo(Trainer::class, 'primo_allenatore_id');
    }

    public function secondoAllenatore()
    {
        return $this->belongsTo(Trainer::class, 'secondo_allenatore_id');
    }

    public function aliases()
    {
        return $this->hasMany(Alias::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function formatHours($hours)
    {
        $formattedTime = Carbon::parse($hours)->format('H:i');
        return $formattedTime;
    }
}

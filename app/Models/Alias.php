<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
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
        'location',
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
        return $this->belongsTo(User::class, 'primo_allenatore_id');
    }

    public function secondoAllenatore()
    {
        return $this->belongsTo(User::class, 'secondo_allenatore_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function compareStudents($groupId, $aliasId)
    {
        // Trova il gruppo e il gruppo alias
        $group = Group::findOrFail($groupId);
        $alias = Alias::findOrFail($aliasId);

        // Recupera gli array studenti_id
        $groupStudentIds = $group->studenti_id ?? [];
        $aliasStudentIds = $alias->studenti_id ?? [];

        // Ottieni gli ID che differiscono tra i due array
        $diffIds = array_merge(
            array_diff($groupStudentIds, $aliasStudentIds),
            array_diff($aliasStudentIds, $groupStudentIds)
        );

        // Filtra i diffIds per ottenere solo quelli che non sono presenti nel gruppo
        $filteredDiffIds = array_diff($diffIds, $groupStudentIds);

        // Trova gli studenti con gli ID filtrati
        $students = User::whereIn('id', $filteredDiffIds)->get();

        // Restituisce gli studenti che differiscono e non sono nel gruppo
        return $students;
    }


    public function formatData($date)
    {
        $formattedDate = Carbon::parse($date)->translatedFormat('l d F');
        return $formattedDate;
    }

    public function formatHours($hours)
    {
        $formattedTime = Carbon::parse($hours)->format('H:i');
        return $formattedTime;
    }

}

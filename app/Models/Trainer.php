<?php

namespace App\Models;

use App\Models\Alias;
use App\Models\Group;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Trainer extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    use TwoFactorAuthenticatable;

    protected $guard = 'trainer';


    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = 'trainers';

    public function primoAllenatoreAliases()
    {
        return $this->hasMany(Alias::class, 'primo_allenatore_id');
    }

    public function secondoAllenatoreAliases()
    {
        return $this->hasMany(Alias::class, 'secondo_allenatore_id');
    }

    public function calcolaStipendioAllenatore($trainerId)
    {
        $trainer = Trainer::findOrFail($trainerId);
        $stipendio = 0;

        // Gruppi Alias dove il trainer è primo allenatore
        $aliasesPrimoAllenatore = Alias::where('primo_allenatore_id', $trainer->id)->get();
        foreach ($aliasesPrimoAllenatore as $alias) {
            if ($alias->condiviso == "true") {
                $stipendio += 15.00;
            } else {
                $stipendio += 22.50;
            }
        }

        // Gruppi Alias dove il trainer è secondo allenatore
        $aliasesSecondoAllenatore = Alias::where('secondo_allenatore_id', $trainer->id)->get();
        foreach ($aliasesSecondoAllenatore as $alias) {
            if ($alias->condiviso == "true") {
                $stipendio += 15.00;
            } else {
                $stipendio += 7.50;
            }
        }

        return $stipendio;
    }

    public function getRecoverableStudent($alias)
    {
        $recoverableStudent = [];
        $students = Student::all();
        foreach ($students as $student) {
            $groups = Group::where('nome', $alias->nome)->get();
            foreach ($groups as $group) {
                if (
                    !in_array($student->id, $group->studenti_id) &&
                    !in_array($student->id, $group->studenti_id) &&
                    $student->Nrecoveries > 0 &&
                    $student->level - 1 <= $alias->livello &&
                    $alias->livello <= $student->level + 2 &&
                    $student->gender == $alias->tipo
                ) {
                    $recoverableStudent[] = $student;
                }
            }
        }
        $recoverableStudent = array_unique($recoverableStudent);
        return $recoverableStudent;
    }
}

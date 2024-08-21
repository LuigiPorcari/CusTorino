<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'cognome',
        'email',
        'password',
        'is_admin',
        'is_trainer',
        'is_corsista',
        'livello',
        'genere',
        'cus_card',
        'visita_medica',
        'pagamento',
        'NrecuperiTemp',
        'Nrecuperi',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_trainer' => 'boolean',
        'is_corsista' => 'boolean',
        'pagamento' => 'boolean',
        'visita_medica' => 'date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Verifica se l'utente ha un ruolo specifico
    public function hasRole($role)
    {
        return $this->{$role} === true;
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function aliases()
    {
        return $this->belongsToMany(Alias::class);
    }

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
        $trainer = User::findOrFail($trainerId);
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
        $students = User::where('is_corsista' , 1)->get();
        foreach ($students as $student) {
            $groups = Group::where('nome', $alias->nome)->get();
            foreach ($groups as $group) {
                if (
                    !in_array($student->id, $alias->studenti_id) &&
                    !in_array($student->id, $group->studenti_id) &&
                    $student->Nrecuperi > 0 &&
                    $student->livello - 1 <= $alias->livello &&
                    $alias->livello <= $student->livello + 2 &&
                    $student->genere == $alias->tipo
                ) {
                    $recoverableStudent[] = $student;
                }
            }
        }
        $recoverableStudent = array_unique($recoverableStudent);
        return $recoverableStudent;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}

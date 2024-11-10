<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\LogsActivity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use LogsActivity;

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
        'Nrecuperi',
        'universitario',
        'trimestrale'
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_trainer' => 'boolean',
        'is_corsista' => 'boolean',
        'pagamento' => 'boolean',
        'visita_medica' => 'boolean',
        'trimestrale' => 'boolean'
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

    public function primoAllenatoreGroups()
    {
        return $this->hasMany(Group::class, 'primo_allenatore_id');
    }

    public function secondoAllenatoreGroups()
    {
        return $this->hasMany(Group::class, 'secondo_allenatore_id');
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
        $students = User::where('is_corsista', 1)->get();
        foreach ($students as $student) {
            $groups = Group::where('nome', $alias->nome)->get();
            foreach ($groups as $group) {
                if (
                    !in_array($student->id, $alias->studenti_id) &&
                    !in_array($student->id, $group->studenti_id) &&
                    // $student->Nrecuperi > 0 &&
                    // $student->livello - 1 <= $alias->livello &&
                    // $alias->livello <= $student->livello + 2 &&
                    $student->genere == $alias->tipo
                ) {
                    $recoverableStudent[] = $student;
                } else {
                    if (
                        !in_array($student->id, $alias->studenti_id) &&
                        !in_array($student->id, $group->studenti_id) && $group->tipo == "misto" && $student->genere == "Misto"
                    ) {
                        $recoverableStudent[] = $student;
                    }
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

    public function canMarkAbsence($alias)
    {
        $string = "";
        // Ottieni la data e l'orario dell'alias
        $aliasDateTime = Carbon::parse($alias->data_allenamento . ' ' . $alias->orario);
        // Ottieni l'orario attuale
        $now = Carbon::now();
        // Calcola la data e l'orario limite per l'incremento (12 ore prima dell'alias)
        $deadline = $aliasDateTime->copy()->subHours(12);
        // Verifica che lo studente abbia la carta CUS, la visita medica e il pagamento effettuato
        if (!Auth::user()->cus_card) {
            $string .= 'Non hai possibilità di recuperare questa assenza perchè non hai una carta CUS valida.<br>';
        }
        if (!Auth::user()->visita_medica) {
            $string .= 'Non hai possibilità di recuperare questa assenza perchè la tua visita medica non è completa.<br>';
        }
        if (!Auth::user()->pagamento) {
            $string .= 'Non hai possibilità di recuperare questa assenza perchè non hai completato il pagamento.<br>';
        }
        // Verifica se l'assenza è richiesta con almeno 12 ore di anticipo
        if ($now->gt($deadline)) {
            $string .= 'Non hai possibilità di recuperare questa assenza perchè il termine delle 12 ore è già passato.<br>';
        }
        // Verifica che tutte le condizioni siano soddisfatte
        if (empty($string) && $now->lte($deadline)) {
            $string = 'Hai la possibilità di recuperare questa assenza.<br>';
        }
        return $string;
    }

    public function countAbsences()
    {
        $countAbsences = 0;
        $student = Auth::user();
        // Itera attraverso tutti i gruppi a cui lo studente è iscritto
        foreach ($student->groups as $group) {
            // Itera attraverso tutti gli alias associati al gruppo
            foreach ($group->aliases as $alias) {
                // Verifica se lo studente non è presente nell'alias (assenza)
                if (!in_array($student->id, $alias->studenti_id)) {
                    $countAbsences += 1;
                }
            }
        }
        return $countAbsences;
    }

    public function countAbsenceTrainer($trainer)
    {
        $countAbsenceTrainer = 0;

        // Itera attraverso tutti i gruppi in cui il trainer è primo allenatore
        foreach ($trainer->primoAllenatoreGroups as $group) {
            foreach ($group->aliases as $alias) {
                // Se il trainer non è né primo né secondo allenatore, conta come assenza
                if ($alias->primo_allenatore_id != $trainer->id && $alias->secondo_allenatore_id != $trainer->id) {
                    $countAbsenceTrainer += 1;
                }
            }
        }

        // Itera attraverso tutti i gruppi in cui il trainer è secondo allenatore
        foreach ($trainer->secondoAllenatoreGroups as $group) {
            foreach ($group->aliases as $alias) {
                // Se il trainer non è né secondo né primo allenatore, conta come assenza
                if ($alias->secondo_allenatore_id != $trainer->id && $alias->primo_allenatore_id != $trainer->id) {
                    $countAbsenceTrainer += 1;
                }
            }
        }

        return $countAbsenceTrainer;
    }

    public function countAttendanceTrainer($trainer)
    {
        $countAttendanceTrainer = 0;

        // Itera attraverso tutti i gruppi in cui il trainer è primo allenatore
        foreach ($trainer->primoAllenatoreGroups as $group) {
            foreach ($group->aliases as $alias) {
                // Conta la presenza se è primo o secondo allenatore
                if ($alias->primo_allenatore_id == $trainer->id || $alias->secondo_allenatore_id == $trainer->id) {
                    $countAttendanceTrainer += 1;
                }
            }
        }

        // Itera attraverso tutti i gruppi in cui il trainer è secondo allenatore
        foreach ($trainer->secondoAllenatoreGroups as $group) {
            foreach ($group->aliases as $alias) {
                // Conta la presenza se è secondo o primo allenatore
                if ($alias->secondo_allenatore_id == $trainer->id || $alias->primo_allenatore_id == $trainer->id) {
                    $countAttendanceTrainer += 1;
                }
            }
        }

        return $countAttendanceTrainer;
    }

    /**
     * Relazione uno-a-molti con Log per le azioni eseguite dall'utente.
     * Un utente può avere molti log di azioni eseguite.
     */
    public function actionLogs()
    {
        return $this->hasMany(Log::class, 'user_id');
    }

    /**
     * Relazione uno-a-molti con Log per le modifiche ricevute dall'utente.
     * Un utente può essere stato modificato in molti log.
     */
    public function modificationLogs()
    {
        return $this->hasMany(Log::class, 'user_modified_id');
    }
}

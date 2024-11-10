<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'action',
        'alias_id',
        'group_id',
        'user_modified_id',
        'changes',
        'custom_action',
        'model_name',
        'model_type',
        'data_allenamento',
        'model_cognome',
        'is_corsista',
        'is_trainer',
        'is_admin',
        'user_name',
        'user_cognome'
    ];

    protected $casts = [
        'changes' => 'array', // Converte il campo `changes` in un array JSON
    ];

    /**
     * Relazione molti-a-uno con il modello User.
     * Ogni log appartiene a un singolo utente che ha eseguito l'operazione.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relazione con l'utente che è stato modificato.
     */
    public function userModified()
    {
        return $this->belongsTo(User::class, 'user_modified_id');
    }

    /**
     * Relazione molti-a-uno con il modello Alias.
     */
    public function alias()
    {
        return $this->belongsTo(Alias::class);
    }

    /**
     * Relazione molti-a-uno con il modello Group.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function formatData($date)
    {
        $formattedDate = Carbon::parse($date)->addHour()->translatedFormat('l d F H:i');
        return $formattedDate;
    }
    public function formatDataMod($date)
    {
        $formattedDate = Carbon::parse($date)->addHour()->translatedFormat('l d F');
        return $formattedDate;
    }
}

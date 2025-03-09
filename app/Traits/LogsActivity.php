<?php
namespace App\Traits;

use App\Models\Log;

trait LogsActivity
{
    /**
     * Metodo per avviare il Trait e definire gli eventi su create, update e delete.
     */
    protected static function bootLogsActivity()
    {
        // Evento 'creating'
        static::created(function ($model) {
            self::logCustomAction('creating', $model);
        });

        // Evento 'updating'
        static::updating(function ($model) {
            self::logCustomAction('updating', $model);
        });

        // Evento 'deleting'
        static::deleted(function ($model) {
            self::logCustomAction('deleting', $model, $model->nome ?? 'Elemento eliminato');
        });
    }

    /**
     * Metodo per registrare un'azione con un tipo personalizzato.
     */
    public static function logCustomAction($action, $model, $deletedName = null, $customActionValue = null)
    {
        $user = auth()->user(); // Recupera l'utente autenticato

        $logData = [
            'user_id' => $user->id ?? null,
            'action' => $action,
            'changes' => $model->getDirty(), // Registra solo i campi modificati
            'custom_action' => $customActionValue ?? ($deletedName ? 'Eliminato' : null), // Valore personalizzato o "Eliminato"
            'model_name' => $model->name ?? $model->nome ?? null, // Aggiungi il nome o l'ID come fallback
            'model_cognome' => $model->cognome ?? null,
            'model_type' => self::getModelType($model), // Aggiungi il tipo del modello come stringa
            'data_allenamento' => $model->data_allenamento ?? null, // Salva 'data_allenamento' se esiste
            'is_corsista' => $user->is_corsista ?? 0, // Aggiungi la variabile is_corsista
            'is_admin' => $user->is_admin ?? 0, // Aggiungi la variabile is_admin
            'is_trainer' => $user->is_trainer ?? 0, // Aggiungi la variabile is_trainer
            'user_name' => $user->name ?? null, // Nome dell'utente autenticato
            'user_cognome' => $user->cognome ?? null, // Cognome dell'utente autenticato
        ];

        // Imposta solo l'ID se l'azione non è 'deleting'
        if ($action !== 'deleting') {
            if ($model instanceof \App\Models\Alias) {
                $logData['alias_id'] = $model->id;
            } elseif ($model instanceof \App\Models\Group) {
                $logData['group_id'] = $model->id;
            } elseif ($model instanceof \App\Models\User) {
                $logData['user_modified_id'] = $model->id;
            }
        }

        // Assicuriamoci che il custom action venga sempre salvato per la conferma assenze
        if ($customActionValue === 'Elemento confermato') {
            $logData['custom_action'] = 'Elemento confermato';
        }

        // Crea il log con i dati specifici
        Log::create($logData);
    }

    /**
     * Metodo per determinare il tipo di modello come stringa.
     */
    private static function getModelType($model)
    {
        if ($model instanceof \App\Models\Alias) {
            return 'Alias';
        } elseif ($model instanceof \App\Models\Group) {
            return 'Group';
        } elseif ($model instanceof \App\Models\User) {
            return 'User';
        } else {
            return 'Altro'; // Tipo generico se non è uno dei modelli specificati
        }
    }
}

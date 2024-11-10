<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LogController extends Controller
{
    public function index()
    {
        $groups = Group::all();
        $users = User::all();

        // Recupera tutti i log degli admin basati sull'attributo is_admin del Log
        $adminLogs = Log::with('user')
            ->where('is_admin', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Filtro per eliminare i duplicati basati su alias_id
        $uniqueByAlias = $adminLogs->unique(function ($item) {
            $attributes = $item->getAttributes();
            unset($attributes['alias_id'], $attributes['id'], $attributes['changes'], $attributes['data_allenamento'], $attributes['model_cognome']);
            return json_encode($attributes);
        })->values();

        // Filtro per eliminare i duplicati basati su group_id all'interno del set già filtrato
        $uniqueByGroup = $uniqueByAlias->unique(function ($item) {
            $attributes = $item->getAttributes();
            unset($attributes['group_id'], $attributes['id'], $attributes['changes']);
            return json_encode($attributes);
        })->values();

        // Filtro finale per eliminare i duplicati basati su tutti gli attributi tranne id, alias_id, group_id e changes
        $finalFilteredLogs = $uniqueByGroup->unique(function ($item) {
            $attributes = $item->getAttributes();
            unset($attributes['id'], $attributes['changes']);
            return json_encode($attributes);
        })->values();

        // Log dei trainer basati sull'attributo is_trainer del Log
        $trainerLogs = Log::with('user')
            ->where('is_trainer', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('created_at') // Rimuove i duplicati basati sul campo 'created_at'
            ->values(); // Reindicizza la collezione

        // Log dei corsisti basati sull'attributo is_corsista del Log e quelli con is_admin, is_trainer, is_corsista false
        $corsistaLogs = Log::with('user')
            ->where('is_corsista', true)
            ->orWhere(function ($query) {
                $query->where('is_admin', false)
                    ->where('is_trainer', false)
                    ->where('is_corsista', false);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->values(); // Reindicizza la collezione

        return view('dashboard.adminLog', compact('finalFilteredLogs', 'trainerLogs', 'corsistaLogs', 'groups', 'users'));
    }

}

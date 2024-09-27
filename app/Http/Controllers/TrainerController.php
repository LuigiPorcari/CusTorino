<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Alias;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrainerController extends Controller
{
    public function dashboard(Request $request)
    {
        // Ottieni l'ID dell'allenatore attualmente autenticato
        $allenatore_id = Auth::user()->id;

        // Ottieni la data odierna
        $today = Carbon::today()->toDateString();

        // Filtra gli alias per primo o secondo allenatore e solo per la data odierna
        $aliasesTrainer = Alias::where(function ($query) use ($allenatore_id) {
            $query->where('primo_allenatore_id', $allenatore_id)
                ->orWhere('secondo_allenatore_id', $allenatore_id);
        })
            ->whereDate('data_allenamento', $today) // Filtra solo gli alias con data odierna
            ->orderBy('orario', 'asc') // Ordina per orario crescente
            ->paginate(10); // Pagina con 10 risultati per pagina

        return view('dashboard.trainer', compact('aliasesTrainer'));
    }

    public function dashboardGroup(Request $request)
    {
        $allenatore_id = Auth::user()->id;
        // Calcola la data odierna meno 15 giorni
        $dateThreshold = Carbon::now()->subDays(15)->toDateString();

        // Ottieni le date di allenamento uniche che non sono inferiori alla data odierna meno 15 giorni
        $dates = Alias::where(function ($query) use ($allenatore_id) {
            $query->where('primo_allenatore_id', $allenatore_id)
                ->orWhere('secondo_allenatore_id', $allenatore_id);
        })
            ->whereDate('data_allenamento', '>=', $dateThreshold) // Filtro per data
            ->distinct('data_allenamento')
            ->pluck('data_allenamento')
            ->sortBy(function ($date) {
                return Carbon::parse($date);
            });

        // Instanzia un oggetto Alias per utilizzare i metodi di formattazione
        $alias = new Alias();

        // Formatta le date
        $availableDates = $dates->map(function ($date) use ($alias) {
            return [
                'formatted' => $alias->formatData($date),
                'original' => $date
            ];
        });

        // Filtro per nome e data
        $query = Alias::where(function ($query) use ($allenatore_id) {
            $query->where('primo_allenatore_id', $allenatore_id)
                ->orWhere('secondo_allenatore_id', $allenatore_id);
        })
            ->whereDate('data_allenamento', '>=', $dateThreshold); // Filtro per data

        if ($request->filled('alias_name')) {
            $query->where('nome', 'like', '%' . $request->alias_name . '%');
        }

        if ($request->filled('alias_date')) {
            $query->whereDate('data_allenamento', $request->alias_date);
        }

        $aliasesTrainer = $query->orderBy('data_allenamento', 'asc')->paginate(50);

        return view('dashboard.trainerGroup', compact('aliasesTrainer', 'availableDates'));
    }

    public function salary()
    {
        $allenatore_id = Auth::user()->id;

        // Recupera tutti gli alias dove l'utente è il primo o secondo allenatore, ordinati per data_allenamento
        $aliasesTrainer = Alias::where(function ($query) use ($allenatore_id) {
            $query->where('primo_allenatore_id', $allenatore_id)
                ->orWhere('secondo_allenatore_id', $allenatore_id);
        })
            ->orderBy('data_allenamento', 'asc') // Ordina per data_allenamento in ordine crescente
            ->get();

        return view('dashboard.trainerSalary', compact('aliasesTrainer'));
    }


}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Availability;
use App\Support\WeeklySlot;

class AvailabilityController extends Controller
{
    // Decidi tu gli orari fissi qui (puoi cambiarli in futuro)
    private array $timeSlots = ['09:00', '14:30', '18:00'];

    // Mostra il form a checkbox per uno specifico utente (studente)
    public function edit(\App\Models\User $user)
    {
        // slot già selezionati
        $selected = $user->availabilities()->get(['slot_key', 'availability_count', 'notes']);
        $selectedKeys = $selected->pluck('slot_key')->all();

        // calcola prefill (unico valore se omogeneo)
        $counts = $selected->pluck('availability_count')->unique()->values();
        $notes = $selected->pluck('notes')
            ->filter(fn($n) => !is_null($n) && $n !== '')
            ->unique()->values();

        $prefillCount = $counts->count() === 1 ? $counts->first() : null; // se eterogeneo => null
        $prefillNotes = $notes->count() === 1 ? $notes->first() : null;

        $hasMixed = [
            'count' => $counts->count() > 1,
            'notes' => $notes->count() > 1,
        ];

        // giorni e timeslots come già fai tu
        $days = [
            1 => 'Lunedì',
            2 => 'Martedì',
            3 => 'Mercoledì',
            4 => 'Giovedì',
            5 => 'Venerdì',
            6 => 'Sabato',
            0 => 'Domenica',
        ];
        $timeSlots = $this->timeSlots;

        return view('availabilities.form', compact(
            'user',
            'selectedKeys',
            'days',
            'timeSlots',
            'prefillCount',
            'prefillNotes',
            'hasMixed'
        ));
    }


    // Salva/aggiorna le disponibilità
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'slots' => ['array'], // può essere vuoto
            'slots.*' => ['regex:/^[0-6]\|[0-2][0-9]:[0-5][0-9]$/'],
            'availability_count' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $countPer = (int) ($data['availability_count'] ?? 1);
        $notes = $data['notes'] ?? null;

        // converte "day|HH:MM" -> slot_key
        $selectedSlotKeys = collect($data['slots'] ?? [])
            ->map(function ($s) {
                [$day, $time] = explode('|', $s);
                return WeeklySlot::toKey((int) $day, $time);
            })
            ->unique()
            ->values()
            ->all();

        // UPSERT: crea o aggiorna i selezionati
        $rows = array_map(function ($key) use ($user, $countPer, $notes) {
            return [
                'user_id' => $user->id,
                'slot_key' => $key,
                'availability_count' => $countPer,
                'notes' => $notes,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $selectedSlotKeys);

        Availability::upsert(
            $rows,
            uniqueBy: ['user_id', 'slot_key'],
            update: ['availability_count', 'notes', 'updated_at']
        );

        // DELETE: rimuovi quelli non più selezionati
        Availability::where('user_id', $user->id)
            ->when(
                count($selectedSlotKeys) > 0,
                fn($q) => $q->whereNotIn('slot_key', $selectedSlotKeys),
                fn($q) => $q // se l'array è vuoto cancella tutte le disponibilità
            )->delete();

        return back()->with('success', 'Disponibilità aggiornate');
    }
    public function groups(Request $request)
    {
        $min = max(1, (int) $request->query('min', 1));

        // Prende tutte le disponibilità con l'utente associato
        $all = Availability::with('user')
            ->orderBy('slot_key')
            ->orderBy('user_id')
            ->get();

        // Raggruppa per slot_key, filtra per minimo membri
        // e ordina i gruppi con Lunedì per primo (Lun..Dom)
        $groups = $all->groupBy('slot_key')
            ->filter(fn($items) => $items->count() >= $min)
            ->sortBy(function ($items, $slotKey) {
                $slotKey = (int) $slotKey;
                $day = intdiv($slotKey, 1440);   // 0=Sunday .. 6=Saturday
                $mins = $slotKey % 1440;          // minuti nel giorno
                $dayMondayFirst = ($day + 6) % 7; // rimappa: 1->0, 2->1, ..., 0(sun)->6
                return $dayMondayFirst * 1440 + $mins;
            });

        // Etichette giorno (0..6 coerente con WeeklySlot::fromKey)
        $dayNames = [
            0 => 'Domenica',
            1 => 'Lunedì',
            2 => 'Martedì',
            3 => 'Mercoledì',
            4 => 'Giovedì',
            5 => 'Venerdì',
            6 => 'Sabato',
        ];

        return view('availabilities.groups', [
            'groups' => $groups,
            'dayNames' => $dayNames,
            'min' => $min,
        ]);
    }
}

<?php

namespace App\Support;

class WeeklySlot
{
    // Convenzione: 0=Sunday .. 6=Saturday (se preferisci Lunedì=0 basta dirlo e adatto)
    public static function toKey(int $dayOfWeek, string $timeHhMm): int
    {
        [$h, $m] = explode(':', $timeHhMm);
        return $dayOfWeek * 1440 + ((int) $h * 60 + (int) $m);
    }

    public static function fromKey(int $slotKey): array
    {
        $day = intdiv($slotKey, 1440);
        $mins = $slotKey % 1440;
        $h = intdiv($mins, 60);
        $m = $mins % 60;
        return [
            'day_of_week' => $day,
            'time' => sprintf('%02d:%02d', $h, $m),
        ];
    }
}

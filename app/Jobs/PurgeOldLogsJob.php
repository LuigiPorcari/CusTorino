<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PurgeOldLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public function __construct(public int $months = 4)
    {
    }

    public function handle(): void
    {
        $lockName = 'bulk:purge_old_logs';
        $locked = DB::selectOne("SELECT GET_LOCK(?, 0) AS l", [$lockName])->l ?? 0;
        if ((int) $locked !== 1) {
            return;
        }

        try {
            $threshold = now()->subMonths($this->months);
            $totalDeleted = 0;

            do {
                $deleted = DB::affectingStatement(
                    "DELETE FROM logs WHERE created_at < ? ORDER BY id LIMIT 10000",
                    [$threshold]
                );
                $totalDeleted += $deleted;

                usleep(200000); // 200ms
            } while ($deleted > 0);

            logger()->info("Purge logs completata", [
                'deleted' => $totalDeleted,
                'threshold' => $threshold->toDateTimeString(),
            ]);
        } finally {
            DB::select("SELECT RELEASE_LOCK(?)", [$lockName]);
        }
    }
}
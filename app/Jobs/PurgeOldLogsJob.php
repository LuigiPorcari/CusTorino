<?php

namespace App\Jobs;

use App\Models\BulkOperation;
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

    public function __construct(public int $operationId, public int $months = 4)
    {
    }

    public function handle(): void
    {
        $op = BulkOperation::findOrFail($this->operationId);

        $op->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        $lockName = 'bulk:purge_old_logs';
        $locked = DB::selectOne("SELECT GET_LOCK(?, 0) AS l", [$lockName])->l ?? 0;

        if ((int) $locked !== 1) {
            $op->update([
                'status' => 'failed',
                'finished_at' => now(),
                'message' => 'Operazione già in corso (lock non acquisito).',
            ]);
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
                usleep(200000);
            } while ($deleted > 0);

            $op->update([
                'status' => 'completed',
                'finished_at' => now(),
                'affected_rows' => $totalDeleted,
                'message' => "Completato. Eliminati {$totalDeleted} log più vecchi di {$this->months} mesi.",
            ]);
        } catch (\Throwable $e) {
            $op->update([
                'status' => 'failed',
                'finished_at' => now(),
                'message' => $e->getMessage(),
            ]);
            throw $e;
        } finally {
            DB::select("SELECT RELEASE_LOCK(?)", [$lockName]);
        }
    }
}
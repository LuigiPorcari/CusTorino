<?php

namespace App\Jobs;

use App\Models\BulkOperation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ResetCorsistiFlagsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public function __construct(public int $operationId)
    {
    }

    public function handle(): void
    {
        $op = BulkOperation::findOrFail($this->operationId);

        $op->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        $lockName = 'bulk:reset_corsisti_flags';
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
            $affected = User::where('is_corsista', true)->update([
                'universitario' => false,
                'pagamento' => false,
                'visita_medica' => false,
                'cus_card' => false,
                'updated_at' => now(),
            ]);

            $op->update([
                'status' => 'completed',
                'finished_at' => now(),
                'affected_rows' => $affected,
                'message' => "Completato. Resettati flag su {$affected} corsisti.",
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
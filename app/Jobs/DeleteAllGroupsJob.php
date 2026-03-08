<?php

namespace App\Jobs;

use App\Models\BulkOperation;
use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class DeleteAllGroupsJob implements ShouldQueue
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

        $lockName = 'bulk:delete_all_groups';
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
            $deletedGroups = 0;

            Group::query()
                ->select('id')
                ->orderBy('id')
                ->chunkById(100, function ($groups) use (&$deletedGroups) {
                    foreach ($groups as $g) {
                        DB::transaction(function () use ($g, &$deletedGroups) {
                            $group = Group::with('aliases')->find($g->id);
                            if (!$group)
                                return;

                            foreach ($group->aliases as $alias) {
                                $alias->users()->sync([]);
                            }

                            $group->aliases()->delete();
                            $group->users()->sync([]);
                            $group->delete();

                            $deletedGroups++;
                        });
                    }
                });

            $op->update([
                'status' => 'completed',
                'finished_at' => now(),
                'affected_rows' => $deletedGroups,
                'message' => "Completato. Eliminati {$deletedGroups} gruppi.",
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
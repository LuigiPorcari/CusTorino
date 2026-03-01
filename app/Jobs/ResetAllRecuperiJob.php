<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ResetAllRecuperiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public function handle(): void
    {
        $lockName = 'bulk:reset_all_recuperi';
        $locked = DB::selectOne("SELECT GET_LOCK(?, 0) AS l", [$lockName])->l ?? 0;
        if ((int) $locked !== 1) {
            return;
        }

        try {
            User::query()->update([
                'Nrecuperi' => 0,
                'updated_at' => now(),
            ]);
        } finally {
            DB::select("SELECT RELEASE_LOCK(?)", [$lockName]);
        }
    }
}
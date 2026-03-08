<?php

namespace App\Http\Controllers;

use App\Models\BulkOperation;

class BulkOperationController extends Controller
{
    // Endpoint JSON per polling
    public function status(int $id)
    {
        $op = BulkOperation::findOrFail($id);

        return response()->json([
            'id' => $op->id,
            'type' => $op->type,
            'status' => $op->status,
            'message' => $op->message,
            'affected_rows' => $op->affected_rows,
            'started_at' => optional($op->started_at)->toDateTimeString(),
            'finished_at' => optional($op->finished_at)->toDateTimeString(),
        ]);
    }
}
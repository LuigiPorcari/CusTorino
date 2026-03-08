<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkOperation extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'message',
        'affected_rows',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
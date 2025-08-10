<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = [
        'user_id',
        'availability_count',
        'slot_key',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use App\Models\Alias;
use App\Models\Group;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $guard = 'student';

    protected $fillable = [
        'nome', 'cognome', 'gender', 'level', 'documentation', 'email', 'password', 'Nrecoveries'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $table = 'students';

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function aliases()
    {
        return $this->belongsToMany(Alias::class);
    }
}

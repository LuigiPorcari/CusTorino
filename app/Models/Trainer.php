<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class Trainer extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    use TwoFactorAuthenticatable;

    protected $guard = 'trainer';

    
    protected $fillable = [
        'nome', 'cognome', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $table = 'trainers';
}

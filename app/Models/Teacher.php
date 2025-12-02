<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use Notifiable;

    protected $guard = 'teacher';

    protected $fillable = [
        'full_name',
        'email', 
        'course',
        'department',
        'cellphone',
        'password',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
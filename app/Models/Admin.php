<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminPlain extends Authenticatable
{
    use HasFactory;

    protected $table = 'admins';
    protected $fillable = ['full_name', 'email', 'password', 'profile_photo'];
    
    // Remove the plain text password setter - let Laravel handle password hashing
    // Add this to automatically hash passwords
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
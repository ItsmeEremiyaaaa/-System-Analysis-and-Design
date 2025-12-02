<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageStaff extends Model
{
    use HasFactory;

    protected $table = 'manage_staff';

    protected $fillable = [
        'full_name',
        'email',
        'department',
        'phone',
        'address',
    ];

    protected $casts = [
        'department' => 'string',
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageStudent extends Model
{
    use HasFactory;

    protected $table = 'manage_students';

    protected $fillable = [
        'student_id',
        'full_name',
        'email',
        'course',
        'section',
        'year_level',
        'phone',
        'address',
    ];

    protected $casts = [
        'course' => 'string',
        'year_level' => 'string',
    ];
}
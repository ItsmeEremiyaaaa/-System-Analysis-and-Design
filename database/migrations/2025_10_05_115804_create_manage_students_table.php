<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manage_students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->enum('course', [
                'BSIT',
                'BSBA', 
                'BSHM',
                'BEED',
                'BSED'
            ]);
            $table->string('section');
            $table->enum('year_level', [
                '1st Year',
                '2nd Year', 
                '3rd Year',
                '4th Year'
            ]);
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manage_students');
    }
};
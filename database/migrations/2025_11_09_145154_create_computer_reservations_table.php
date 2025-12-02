<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('computer_reservations')) {
            Schema::create('computer_reservations', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->enum('lab', [
                    'Computer Laboratory 1',
                    'Computer Laboratory 2',
                    'Computer Laboratory Netlab'
                ]);
                $table->text('purpose');
                $table->date('date');
                $table->time('start_time');
                $table->time('end_time');
                $table->string('requester');
                $table->integer('participants')->nullable();
                $table->text('requirements')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
                $table->text('admin_notes')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->string('reviewed_by')->nullable();
                $table->timestamps();

                $table->index(['date', 'lab']);
                $table->index('status');
                $table->index('requester');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('computer_reservations');
    }
};

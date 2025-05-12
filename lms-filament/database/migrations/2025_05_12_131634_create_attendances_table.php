<?php
// database/migrations/2025_05_13_000000_create_attendances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->dateTime('session_date');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('hadir');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Unique constraint to prevent duplicate attendance records
            $table->unique(['user_id', 'course_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
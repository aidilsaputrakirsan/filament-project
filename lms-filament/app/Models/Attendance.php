<?php
// app/Models/Attendance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'session_date',
        'status', // hadir, izin, sakit, alpa
        'notes',
    ];

    protected $casts = [
        'session_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'course_id');
    }
    
    // Alias untuk backward compatibility
    public function course()
    {
        return $this->mataKuliah();
    }
}
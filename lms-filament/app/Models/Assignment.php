<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    // Tambahkan semua kolom yang bisa diisi secara massal ke sini
    protected $fillable = [
        'title',
        'description',
        'course_id',
        'due_date',
        'max_score',
        // tambahkan kolom lain yang perlu mass-assignment
    ];

    // Relasi dengan kursus
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relasi dengan submission
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
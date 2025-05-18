<?php
// app/Models/Assignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'mata_kuliah_id', // Ubah dari course_id
        'due_date',
        'max_score',
        'attachment',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
    
    // Alias untuk backward compatibility
    public function course()
    {
        return $this->mataKuliah();
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
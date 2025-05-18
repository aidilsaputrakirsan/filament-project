<?php
// app/Models/Lesson.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'content',
        'course_id',
        'order',
    ];
    
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
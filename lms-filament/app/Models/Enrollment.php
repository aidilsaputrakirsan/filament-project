<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'course_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function mataKuliah() {
        return $this->belongsTo(MataKuliah::class, 'course_id'); // Tetap gunakan course_id untuk backward compatibility
    }
}
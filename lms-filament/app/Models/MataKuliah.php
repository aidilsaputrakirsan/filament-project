<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'title',
        'kode',
        'description',
        'thumbnail',
        'user_id',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'mata_kuliah_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'mata_kuliah_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'mata_kuliah_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'mata_kuliah_id');
    }

    public function practicalModules(): HasMany
    {
        return $this->hasMany(PracticalModule::class, 'mata_kuliah_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'mata_kuliah_id');
    }

    public function getStudents()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'mata_kuliah_id', 'user_id')
            ->where('role', 'mahasiswa');
    }

    public function getInstructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
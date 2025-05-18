<?php
// app/Models/User.php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim_nip',
        'profile_photo',
        'language_preference',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya admin dan dosen yang bisa akses panel
        return in_array($this->role, ['admin', 'dosen']);
    }
    
    public function mataKuliah()
    {
        if ($this->role === 'dosen') {
            return $this->hasMany(MataKuliah::class);
        }
        return null;
    }
    
    // Alias untuk backward compatibility
    public function courses()
    {
        return $this->mataKuliah();
    }
    
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
    
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliah::class, 'enrollments', 'user_id', 'mata_kuliah_id')
            ->withTimestamps()
            ->withPivot(['status', 'enrolled_at']);
    }
    
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
    
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }
    
    public function setLanguagePreference(string $locale): void
    {
        $this->update(['language_preference' => $locale]);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'dosen';
    }

    public function isStudent(): bool
    {
        return $this->role === 'mahasiswa';
    }
}
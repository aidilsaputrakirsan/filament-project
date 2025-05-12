<?php
// app/Policies/AttendancePolicy.php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Hanya admin dan dosen yang bisa melihat daftar presensi
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, Attendance $attendance): bool
    {
        // Admin bisa melihat semua presensi
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa melihat presensi dari kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->courses->contains($attendance->course_id);
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Hanya admin dan dosen yang bisa membuat presensi
        return $user->isAdmin() || $user->isTeacher();
    }

    public function update(User $user, Attendance $attendance): bool
    {
        // Admin bisa update semua presensi
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa update presensi dari kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->courses->contains($attendance->course_id);
        }

        return false;
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        // Admin bisa hapus semua presensi
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa hapus presensi dari kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->courses->contains($attendance->course_id);
        }

        return false;
    }
}
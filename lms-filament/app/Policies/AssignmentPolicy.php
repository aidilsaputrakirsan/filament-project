<?php
// app/Policies/AssignmentPolicy.php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Hanya admin dan dosen yang bisa melihat daftar tugas
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, Assignment $assignment): bool
    {
        // Admin bisa melihat semua tugas
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa melihat tugas dari kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->courses->contains($assignment->course_id);
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Hanya admin dan dosen yang bisa membuat tugas
        return $user->isAdmin() || $user->isTeacher();
    }

    public function update(User $user, Assignment $assignment): bool
    {
        // Admin bisa update semua tugas
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa update tugas dari kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->courses->contains($assignment->course_id);
        }

        return false;
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        // Admin bisa hapus semua tugas
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa hapus tugas dari kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->courses->contains($assignment->course_id);
        }

        return false;
    }
}
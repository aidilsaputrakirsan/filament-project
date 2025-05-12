<?php
// app/Policies/CoursePolicy.php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // Semua user bisa melihat daftar kursus
    }

    public function view(User $user, Course $course): bool
    {
        // Admin bisa melihat semua kursus
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa melihat kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->id === $course->user_id;
        }

        // Mahasiswa hanya bisa melihat kursus yang dipublish dan terdaftar
        if ($user->isStudent()) {
            return $course->is_published && $user->enrolledCourses->contains($course->id);
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Hanya admin dan dosen yang bisa membuat kursus
        return $user->isAdmin() || $user->isTeacher();
    }

    public function update(User $user, Course $course): bool
    {
        // Admin bisa update semua kursus
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa update kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->id === $course->user_id;
        }

        return false;
    }

    public function delete(User $user, Course $course): bool
    {
        // Admin bisa hapus semua kursus
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa hapus kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->id === $course->user_id;
        }

        return false;
    }
}
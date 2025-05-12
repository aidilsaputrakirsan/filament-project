<?php
// app/Policies/QuizPolicy.php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Hanya admin dan dosen yang bisa melihat daftar kuis
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, Quiz $quiz): bool
    {
        // Admin bisa melihat semua kuis
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa melihat kuis dari kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->courses->contains($quiz->course_id);
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Hanya admin dan dosen yang bisa membuat kuis
        return $user->isAdmin() || $user->isTeacher();
    }

    public function update(User $user, Quiz $quiz): bool
    {
        // Admin bisa update semua kuis
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa update kuis dari kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->courses->contains($quiz->course_id);
        }

        return false;
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        // Admin bisa hapus semua kuis
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa hapus kuis dari kursus yang dibuat olehnya
        if ($user->isTeacher()) {
            return $user->courses->contains($quiz->course_id);
        }

        return false;
    }
}
<?php
// app/Policies/UserPolicy.php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Hanya admin yang bisa melihat daftar user
        return $user->isAdmin();
    }

    public function view(User $user, User $model): bool
    {
        // Admin bisa melihat semua user
        if ($user->isAdmin()) {
            return true;
        }

        // User lain hanya bisa melihat profil diri sendiri
        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        // Hanya admin yang bisa membuat user baru
        return $user->isAdmin();
    }

    public function update(User $user, User $model): bool
    {
        // Admin bisa update semua user
        if ($user->isAdmin()) {
            return true;
        }

        // Dosen hanya bisa update dirinya sendiri
        if ($user->isTeacher()) {
            return $user->id === $model->id;
        }

        return false;
    }

    public function delete(User $user, User $model): bool
    {
        // Hanya admin yang bisa menghapus user
        // Dan admin tidak bisa menghapus dirinya sendiri
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
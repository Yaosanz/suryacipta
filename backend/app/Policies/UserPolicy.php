<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->role->canManageUsers();
    }

    public function view(User $user, User $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, User $model): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, User $model): bool
    {
        if (! $this->viewAny($user) || $user->is($model)) {
            return false;
        }

        if ($model->role !== UserRole::SuperAdmin) {
            return true;
        }

        return User::query()
            ->where('role', UserRole::SuperAdmin)
            ->where('is_active', true)
            ->count() > 1;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}

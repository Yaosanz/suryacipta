<?php

namespace App\Policies;

use App\Enums\PublicationStatus;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class ContentPolicy
{
    public function before(User $user): ?bool
    {
        return $user->is_active ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Model $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Model $model): bool
    {
        if ($user->role === UserRole::Editor) {
            $isLive = $model->getAttribute('is_published') === true
                || $model->getAttribute('is_enabled') === true
                || $model->getAttribute('status') === PublicationStatus::Published;

            return ! $isLive;
        }

        return true;
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->role->canDeleteContent();
    }

    public function deleteAny(User $user): bool
    {
        return $user->role->canDeleteContent();
    }

    public function restore(User $user, Model $model): bool
    {
        return $user->role->canDeleteContent();
    }

    public function restoreAny(User $user): bool
    {
        return $user->role->canDeleteContent();
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return $user->role->canManageUsers();
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->role->canManageUsers();
    }

    public function replicate(User $user, Model $model): bool
    {
        return true;
    }

    public function reorder(User $user): bool
    {
        return true;
    }
}

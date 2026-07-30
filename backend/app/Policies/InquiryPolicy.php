<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;

class InquiryPolicy
{
    public function before(User $user): ?bool
    {
        return $user->is_active ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return $user->role->canViewInquiries();
    }

    public function view(User $user, Inquiry $inquiry): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Inquiry $inquiry): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function delete(User $user, Inquiry $inquiry): bool
    {
        return $user->is_active && $user->role->canManageUsers();
    }

    public function deleteAny(User $user): bool
    {
        return $user->role->canManageUsers();
    }

    public function restore(User $user, Inquiry $inquiry): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, Inquiry $inquiry): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Inquiry $inquiry): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return false;
    }
}

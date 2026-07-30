<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;

class InquiryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->role->canViewInquiries();
    }

    public function view(User $user, Inquiry $inquiry): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Inquiry $inquiry): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, Inquiry $inquiry): bool
    {
        return $user->is_active && $user->role->canManageUsers();
    }

    public function deleteAny(User $user): bool
    {
        return $user->is_active && $user->role->canManageUsers();
    }
}

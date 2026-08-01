<?php

namespace App\Policies;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SiteSettingPolicy
{
    public function before(User $user): ?bool
    {
        return $user->is_active ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return $user->role->canManageSettings();
    }

    public function view(User $user, SiteSetting $setting): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user) && ! DB::table('site_settings')->exists();
    }

    public function update(User $user, SiteSetting $setting): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, SiteSetting $setting): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, SiteSetting $setting): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, SiteSetting $setting): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, SiteSetting $setting): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return false;
    }
}

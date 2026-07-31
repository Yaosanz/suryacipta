<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Models\Concerns\RecordsDetailedActivity;
use Database\Factories\UserFactory;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['name', 'email', 'avatar_path', 'password', 'role', 'is_active', 'last_login_at'])]
#[Hidden(['password', 'remember_token', 'app_authentication_secret', 'app_authentication_recovery_codes'])]
class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use InteractsWithAppAuthentication;
    use InteractsWithAppAuthenticationRecovery;
    use LogsActivity;
    use Notifiable;
    use RecordsDetailedActivity;

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin' && $this->is_active;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (filled($this->avatar_path) && Storage::disk('public')->exists($this->avatar_path)) {
            return Storage::disk('public')->url($this->avatar_path);
        }

        $initials = collect(preg_split('/\s+/', trim($this->name)) ?: [])
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->join('');
        $initials = $initials !== '' ? $initials : 'SC';
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 96 96">'
            .'<rect width="96" height="96" rx="48" fill="#17394a"/>'
            .'<text x="48" y="58" text-anchor="middle" font-family="Arial,sans-serif" font-size="34" font-weight="700" fill="#f3e8c8">'
            .e($initials)
            .'</text></svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('users')
            ->logOnly(['name', 'email', 'role', 'is_active'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (User $user): void {
            if ($user->isDirty('avatar_path') && filled($user->getOriginal('avatar_path'))) {
                Storage::disk('public')->delete($user->getOriginal('avatar_path'));
            }
        });

        static::deleted(function (User $user): void {
            if (filled($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
        });
    }
}

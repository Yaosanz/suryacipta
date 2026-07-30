<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case ContentManager = 'content_manager';
    case Editor = 'editor';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::ContentManager => 'Content Manager',
            self::Editor => 'Editor',
        };
    }

    public function canPublish(): bool
    {
        return $this !== self::Editor;
    }

    public function canDeleteContent(): bool
    {
        return $this !== self::Editor;
    }

    public function canManageUsers(): bool
    {
        return $this === self::SuperAdmin;
    }

    public function canManageSettings(): bool
    {
        return $this !== self::Editor;
    }

    public function canViewInquiries(): bool
    {
        return $this !== self::Editor;
    }
}

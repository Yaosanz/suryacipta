<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class AuditContext
{
    /**
     * @return array<string, mixed>
     */
    public static function capture(?Model $subject = null): array
    {
        $request = app()->runningInConsole() ? null : request();
        $user = auth()->user();

        return [
            'subject_label' => $subject ? self::subjectLabel($subject) : null,
            'actor_email' => $user?->email,
            'actor_role' => $user?->role?->value,
            'ip_address' => $request?->ip(),
            'http_method' => $request?->method(),
            'route' => $request?->route()?->getName() ?? $request?->path(),
            'user_agent' => filled($request?->userAgent())
                ? Str::limit((string) $request->userAgent(), 500, '')
                : null,
            'occurred_at' => now()->toIso8601String(),
        ];
    }

    public static function subjectLabel(Model $subject): string
    {
        foreach (['name', 'title', 'question', 'company_name', 'section_key', 'alt_text', 'email'] as $field) {
            $value = $subject->getAttribute($field);

            if (filled($value)) {
                return Str::limit((string) $value, 160);
            }
        }

        return class_basename($subject).' #'.$subject->getKey();
    }
}

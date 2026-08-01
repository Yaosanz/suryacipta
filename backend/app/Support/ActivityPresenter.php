<?php

namespace App\Support;

use BackedEnum;
use Spatie\Activitylog\Models\Activity;

final class ActivityPresenter
{
    public static function subjectType(?string $state): string
    {
        return match (class_basename($state ?? '-')) {
            'BlogPost' => 'Artikel',
            'Faq' => 'FAQ',
            'Inquiry' => 'Permintaan penawaran',
            'MediaAsset' => 'Media',
            'PageSection' => 'Bagian halaman',
            'PortfolioProject' => 'Portofolio',
            'Product' => 'Produk',
            'SiteSetting' => 'Profil website',
            'Testimonial' => 'Testimoni',
            'TeamMember' => 'Anggota tim',
            'User' => 'Pengguna',
            default => class_basename($state ?? '-'),
        };
    }

    public static function event(?string $state): string
    {
        return match ($state) {
            'created' => 'Dibuat',
            'updated' => 'Diubah',
            'deleted' => 'Dihapus',
            'restored' => 'Dipulihkan',
            'login' => 'Login',
            'logout' => 'Logout',
            default => ucfirst($state ?? '-'),
        };
    }

    /**
     * @return array<int, array{field: string, old: string, new: string}>
     */
    public static function changes(Activity $activity): array
    {
        $changes = is_object($activity->attribute_changes) && method_exists($activity->attribute_changes, 'toArray')
            ? $activity->attribute_changes->toArray()
            : (array) $activity->attribute_changes;
        $newValues = (array) ($changes['attributes'] ?? []);
        $oldValues = (array) ($changes['old'] ?? []);

        return collect(array_unique([...array_keys($oldValues), ...array_keys($newValues)]))
            ->reject(fn (string $field): bool => in_array($field, [
                'password',
                'remember_token',
                'app_authentication_secret',
                'app_authentication_recovery_codes',
                'message',
            ], true))
            ->map(fn (string $field): array => [
                'field' => self::field($field),
                'old' => self::value($oldValues[$field] ?? null),
                'new' => self::value($newValues[$field] ?? null),
            ])
            ->values()
            ->all();
    }

    private static function field(string $field): string
    {
        return match ($field) {
            'is_published', 'is_enabled' => 'Status tayang',
            'is_featured' => 'Konten unggulan',
            'image_path', 'featured_image_path', 'photo_path', 'logo_path' => 'Berkas gambar',
            'video_path' => 'Berkas video',
            'video_width' => 'Lebar video',
            'video_height' => 'Tinggi video',
            'video_duration' => 'Durasi video',
            'video_size' => 'Ukuran video',
            'sort_order' => 'Urutan tampil',
            'published_at' => 'Waktu terbit',
            'handled_by' => 'Ditangani oleh',
            'handled_at' => 'Waktu ditangani',
            default => ucfirst(str_replace('_', ' ', $field)),
        };
    }

    private static function value(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        if ($value instanceof BackedEnum) {
            return (string) $value->value;
        }

        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '-';
        }

        return (string) $value;
    }
}

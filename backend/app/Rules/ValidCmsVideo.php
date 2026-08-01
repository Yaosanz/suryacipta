<?php

namespace App\Rules;

use App\Support\VideoMetadata;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class ValidCmsVideo implements ValidationRule
{
    public function __construct(
        private readonly bool $allowImages = false,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            return;
        }

        $mimeType = $value instanceof UploadedFile
            ? (string) $value->getMimeType()
            : (is_string($value) && Storage::disk('public')->exists($value)
                ? (string) Storage::disk('public')->mimeType($value)
                : '');

        if ($this->allowImages && str_starts_with($mimeType, 'image/')) {
            return;
        }

        if ($mimeType !== 'video/mp4') {
            $fail('Video harus menggunakan format MP4.');

            return;
        }

        $path = $value instanceof UploadedFile
            ? $value->getRealPath()
            : Storage::disk('public')->path((string) $value);

        try {
            $metadata = app(VideoMetadata::class)->analyze($path);
        } catch (Throwable) {
            $fail('Metadata video tidak dapat diverifikasi. Gunakan MP4 standar yang tidak rusak.');

            return;
        }

        if (
            $metadata['width'] < 1280
            || $metadata['height'] < 720
            || $metadata['width'] > 1920
            || $metadata['height'] > 1080
        ) {
            $fail('Resolusi video harus antara 1280x720 dan 1920x1080.');
        }

        $ratio = $metadata['width'] / $metadata['height'];

        if (abs($ratio - (16 / 9)) > 0.02) {
            $fail('Rasio video harus 16:9 agar tidak terpotong pada website.');
        }

        if ($metadata['duration'] > 120) {
            $fail('Durasi video maksimal 120 detik.');
        }
    }
}

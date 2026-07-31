<?php

namespace App\Filament\Forms;

use App\Rules\ValidCmsVideo;
use Filament\Forms\Components\FileUpload;

final class CmsMediaUpload
{
    public static function make(
        string $field,
        string $label,
        string $directory,
    ): FileUpload {
        return FileUpload::make($field)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'video/mp4'])
            ->maxSize(40960)
            ->rules([new ValidCmsVideo(allowImages: true)])
            ->imagePreviewHeight('240')
            ->panelAspectRatio('16:9')
            ->panelLayout('integrated')
            ->previewable()
            ->openable()
            ->downloadable()
            ->preventFilePathTampering()
            ->helperText('Gambar: JPG/PNG/WebP maksimal 5 MB. Video: MP4 16:9, 720p-1080p, maksimal 120 detik dan 40 MB.');
    }
}

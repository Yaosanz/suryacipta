<?php

namespace App\Filament\Forms;

use App\Rules\ValidCmsVideo;
use Filament\Forms\Components\FileUpload;

final class CmsVideoUpload
{
    public static function make(
        string $field,
        string $label,
        string $directory,
        int $maxSize = 40960,
    ): FileUpload {
        return FileUpload::make($field)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->acceptedFileTypes(['video/mp4'])
            ->maxSize($maxSize)
            ->rules([new ValidCmsVideo])
            ->previewable()
            ->openable()
            ->downloadable()
            ->preventFilePathTampering()
            ->helperText('MP4 16:9, resolusi 1280x720 sampai 1920x1080, maksimal 120 detik dan 40 MB.');
    }
}

<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\FileUpload;

final class CmsImageUpload
{
    public static function make(
        string $field,
        string $label,
        string $directory,
        int $maxSize = 5120,
    ): FileUpload {
        return FileUpload::make($field)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->image()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxSize($maxSize)
            ->imageEditor()
            ->orientImagesFromExif()
            ->preventFilePathTampering()
            ->helperText('Format JPG, PNG, atau WebP. Ukuran maksimal '.($maxSize / 1024).' MB.');
    }
}

<?php

namespace App\Filament\Forms;

use App\Rules\HasTransparentBackground;
use Filament\Forms\Components\FileUpload;

final class CmsLogoUpload
{
    public static function make(string $field, string $label, string $directory): FileUpload
    {
        return FileUpload::make($field)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->image()
            ->acceptedFileTypes(['image/png', 'image/webp'])
            ->rules([new HasTransparentBackground])
            ->maxSize(2048)
            ->imagePreviewHeight('240')
            ->panelAspectRatio('1:1')
            ->panelLayout('integrated')
            ->imageEditor()
            ->imageEditorAspectRatioOptions(['1:1'])
            ->orientImagesFromExif()
            ->openable()
            ->downloadable()
            ->preventFilePathTampering()
            ->helperText('Rasio 1:1, ideal 1000 x 1000 piksel. Wajib PNG/WebP transparan, maksimal 2 MB.');
    }
}

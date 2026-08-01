<?php

namespace App\Filament\Forms;

use App\Rules\ValidCmsImage;
use Filament\Forms\Components\FileUpload;

final class CmsImageUpload
{
    public static function make(
        string $field,
        string $label,
        string $directory,
        int $maxSize = 5120,
        string $aspectRatio = '4:3',
        string $recommendedSize = '1600 x 1200',
        int $minWidth = 1200,
        int $minHeight = 900,
        ?float $validationRatio = 4 / 3,
    ): FileUpload {
        return FileUpload::make($field)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->image()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->rules([new ValidCmsImage(
                minWidth: $minWidth,
                minHeight: $minHeight,
                aspectRatio: $validationRatio,
                aspectRatioLabel: $aspectRatio,
            )])
            ->maxSize($maxSize)
            ->imagePreviewHeight('240')
            ->panelAspectRatio($aspectRatio)
            ->panelLayout('integrated')
            ->imageEditor()
            ->imageEditorAspectRatioOptions([$aspectRatio])
            ->orientImagesFromExif()
            ->openable()
            ->downloadable()
            ->preventFilePathTampering()
            ->helperText(
                "Rasio {$aspectRatio}, rekomendasi {$recommendedSize} piksel. JPG, PNG, atau WebP maksimal ".
                ($maxSize / 1024).' MB.',
            );
    }
}

<?php

namespace App\Filament\Forms;

use Filament\Schemas\Components\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

final class CmsMediaPreview
{
    public static function make(
        string $pathField,
        string $type = 'image',
        ?string $altField = null,
        ?string $mimeField = null,
    ): View {
        return View::make('filament.components.media-preview')
            ->viewData(function (?Model $record) use ($pathField, $type, $altField, $mimeField): array {
                $path = $record?->getAttribute($pathField);
                $mimeType = $mimeField ? $record?->getAttribute($mimeField) : null;
                $resolvedType = filled($mimeType)
                    ? (str_starts_with((string) $mimeType, 'video/') ? 'video' : 'image')
                    : $type;

                return [
                    'alt' => $altField ? $record?->getAttribute($altField) : null,
                    'path' => $path,
                    'type' => $resolvedType,
                    'url' => filled($path) ? Storage::disk('public')->url($path) : null,
                ];
            })
            ->visible(fn (?Model $record): bool => filled($record?->getAttribute($pathField)))
            ->columnSpanFull();
    }
}

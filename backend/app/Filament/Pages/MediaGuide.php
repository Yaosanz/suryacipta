<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class MediaGuide extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Panduan Media';

    protected static string|\UnitEnum|null $navigationGroup = 'Media';

    protected static ?int $navigationSort = 20;

    protected static ?string $title = 'Panduan ukuran media';

    protected string $view = 'filament.pages.media-guide';

    public function getSubheading(): ?string
    {
        return 'Gunakan rasio dan resolusi berikut agar gambar tetap tajam, tidak terpotong, dan konsisten di semua perangkat.';
    }
}

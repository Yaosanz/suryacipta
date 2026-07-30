<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use Filament\Resources\Pages\EditRecord;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    public function getSubheading(): ?string
    {
        return 'Perubahan di halaman ini berlaku sebagai identitas utama website.';
    }
}

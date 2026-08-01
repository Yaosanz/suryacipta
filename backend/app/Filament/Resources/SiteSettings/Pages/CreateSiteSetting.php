<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Filament\Support\Pages\CreateCmsRecord;

class CreateSiteSetting extends CreateCmsRecord
{
    protected static string $resource = SiteSettingResource::class;
}

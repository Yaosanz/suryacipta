<?php

namespace App\Filament\Support\Pages;

use Filament\Resources\Pages\CreateRecord;

abstract class CreateCmsRecord extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

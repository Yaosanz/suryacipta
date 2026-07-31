<?php

namespace App\Filament\Support\Pages;

use Filament\Resources\Pages\EditRecord;

abstract class EditCmsRecord extends EditRecord
{
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

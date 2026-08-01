<?php

namespace App\Filament\Resources\PageSections\Pages;

use App\Filament\Resources\PageSections\PageSectionResource;
use App\Filament\Support\Pages\EditCmsRecord;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;

class EditPageSection extends EditCmsRecord
{
    protected static string $resource = PageSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

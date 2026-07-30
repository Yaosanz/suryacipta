<?php

namespace App\Filament\Resources\PageSections\Pages;

use App\Filament\Resources\PageSections\PageSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPageSections extends ListRecords
{
    protected static string $resource = PageSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

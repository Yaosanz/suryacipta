<?php

namespace App\Filament\Resources\PortfolioProjects\Pages;

use App\Filament\Resources\PortfolioProjects\PortfolioProjectResource;
use App\Filament\Support\Pages\EditCmsRecord;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;

class EditPortfolioProject extends EditCmsRecord
{
    protected static string $resource = PortfolioProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

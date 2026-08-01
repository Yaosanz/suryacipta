<?php

namespace App\Filament\Resources\MediaAssets\Pages;

use App\Filament\Resources\MediaAssets\MediaAssetResource;
use App\Filament\Support\Pages\EditCmsRecord;
use App\Support\MediaAssetMetadata;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;

class EditMediaAsset extends EditCmsRecord
{
    protected static string $resource = MediaAssetResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = array_merge(
            $data,
            app(MediaAssetMetadata::class)->forPublicPath($data['file_path']),
        );

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

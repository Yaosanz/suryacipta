<?php

namespace App\Filament\Resources\MediaAssets\Pages;

use App\Filament\Resources\MediaAssets\MediaAssetResource;
use App\Filament\Support\Pages\CreateCmsRecord;
use App\Support\MediaAssetMetadata;

class CreateMediaAsset extends CreateCmsRecord
{
    protected static string $resource = MediaAssetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = array_merge(
            $data,
            app(MediaAssetMetadata::class)->forPublicPath($data['file_path']),
        );
        $data['uploaded_by'] = auth()->id();

        return $data;
    }
}

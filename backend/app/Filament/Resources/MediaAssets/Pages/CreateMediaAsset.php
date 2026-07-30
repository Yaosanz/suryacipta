<?php

namespace App\Filament\Resources\MediaAssets\Pages;

use App\Filament\Resources\MediaAssets\MediaAssetResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateMediaAsset extends CreateRecord
{
    protected static string $resource = MediaAssetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $disk = Storage::disk('public');
        $data['disk'] = 'public';
        $data['original_name'] = basename($data['file_path']);
        $data['mime_type'] = (string) $disk->mimeType($data['file_path']);
        $data['size'] = $disk->size($data['file_path']);
        $data['uploaded_by'] = auth()->id();

        return $data;
    }
}

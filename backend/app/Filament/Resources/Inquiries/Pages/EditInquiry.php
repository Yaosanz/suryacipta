<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Support\Pages\EditCmsRecord;
use Filament\Actions\DeleteAction;

class EditInquiry extends EditCmsRecord
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

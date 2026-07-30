<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use Filament\Resources\Pages\ListRecords;

class ListInquiries extends ListRecords
{
    protected static string $resource = InquiryResource::class;

    public function getSubheading(): ?string
    {
        return 'Tindak lanjuti pesan yang dikirim calon pelanggan dari formulir website.';
    }
}

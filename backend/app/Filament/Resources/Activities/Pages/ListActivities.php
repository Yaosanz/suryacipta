<?php

namespace App\Filament\Resources\Activities\Pages;

use App\Filament\Resources\Activities\ActivityResource;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    public function getSubheading(): ?string
    {
        return 'Riwayat perubahan penting di CMS. Data ini hanya dapat dilihat dan tidak dapat diubah.';
    }
}

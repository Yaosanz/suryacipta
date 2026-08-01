<?php

namespace App\Filament\Resources\Faqs\Pages;

use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Support\Pages\CreateCmsRecord;

class CreateFaq extends CreateCmsRecord
{
    protected static string $resource = FaqResource::class;
}

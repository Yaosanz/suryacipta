<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Support\Pages\CreateCmsRecord;

class CreateProduct extends CreateCmsRecord
{
    protected static string $resource = ProductResource::class;
}

<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Support\Pages\CreateCmsRecord;

class CreateUser extends CreateCmsRecord
{
    protected static string $resource = UserResource::class;
}

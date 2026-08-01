<?php

namespace App\Filament\Resources\TeamMembers\Pages;

use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Filament\Support\Pages\CreateCmsRecord;

class CreateTeamMember extends CreateCmsRecord
{
    protected static string $resource = TeamMemberResource::class;
}

<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Filament\Support\Pages\CreateCmsRecord;

class CreateBlogPost extends CreateCmsRecord
{
    protected static string $resource = BlogPostResource::class;
}

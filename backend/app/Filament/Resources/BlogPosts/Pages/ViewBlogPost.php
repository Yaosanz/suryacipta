<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Filament\Support\Pages\ViewCmsRecord;

class ViewBlogPost extends ViewCmsRecord
{
    protected static string $resource = BlogPostResource::class;
}

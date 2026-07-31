<?php

namespace App\Filament\Resources\Testimonials\Pages;

use App\Filament\Resources\Testimonials\TestimonialResource;
use App\Filament\Support\Pages\CreateCmsRecord;

class CreateTestimonial extends CreateCmsRecord
{
    protected static string $resource = TestimonialResource::class;
}

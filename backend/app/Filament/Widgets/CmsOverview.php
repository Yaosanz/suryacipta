<?php

namespace App\Filament\Widgets;

use App\Enums\InquiryStatus;
use App\Enums\PublicationStatus;
use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\BlogPost;
use App\Models\Inquiry;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CmsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan CMS';

    protected function getStats(): array
    {
        $stats = [
            Stat::make('Produk terbit', Product::query()->published()->count())
                ->url(ProductResource::getUrl()),
            Stat::make('Draft artikel', BlogPost::query()->where('status', PublicationStatus::Draft)->count())
                ->url(BlogPostResource::getUrl()),
        ];

        if (auth()->user()->role->canViewInquiries()) {
            $stats[] = Stat::make('Permintaan baru', Inquiry::query()->where('status', InquiryStatus::New)->count())
                ->url(InquiryResource::getUrl())
                ->color('warning');
        }

        return $stats;
    }
}

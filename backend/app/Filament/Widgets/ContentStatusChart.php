<?php

namespace App\Filament\Widgets;

use App\Enums\PublicationStatus;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\PageSection;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Filament\Widgets\ChartWidget;

class ContentStatusChart extends ChartWidget
{
    protected static ?int $sort = 30;

    protected ?string $heading = 'Status kesiapan konten';

    protected ?string $description = 'Perbandingan konten yang sudah tayang dan yang masih perlu ditinjau.';

    protected ?string $maxHeight = '280px';

    protected bool $isCollapsible = true;

    protected function getData(): array
    {
        $published = Product::query()->where('is_published', true)->count()
            + PortfolioProject::query()->where('is_published', true)->count()
            + BlogPost::query()->where('status', PublicationStatus::Published)->count()
            + Faq::query()->where('is_published', true)->count()
            + Testimonial::query()->where('is_published', true)->count()
            + TeamMember::query()->where('is_published', true)->count()
            + PageSection::query()->where('is_enabled', true)->count();
        $review = Product::query()->where('is_published', false)->count()
            + PortfolioProject::query()->where('is_published', false)->count()
            + BlogPost::query()->where('status', PublicationStatus::Draft)->count()
            + Faq::query()->where('is_published', false)->count()
            + Testimonial::query()->where('is_published', false)->count()
            + TeamMember::query()->where('is_published', false)->count()
            + PageSection::query()->where('is_enabled', false)->count();

        return [
            'datasets' => [[
                'data' => [$published, $review],
                'backgroundColor' => ['#0f766e', '#d4a33c'],
                'borderWidth' => 0,
            ]],
            'labels' => ['Sudah tayang', 'Perlu ditinjau'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

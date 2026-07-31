<?php

namespace App\Filament\Widgets;

use App\Enums\InquiryStatus;
use App\Enums\PublicationStatus;
use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\PageSections\PageSectionResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Inquiry;
use App\Models\PageSection;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Activitylog\Models\Activity;

class CmsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 10;

    protected ?string $heading = 'Ringkasan hari ini';

    protected ?string $description = 'Angka diperbarui otomatis setiap 30 detik. Klik kartu untuk membuka data terkait.';

    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $published = Product::query()->published()->count()
            + PortfolioProject::query()->published()->count()
            + BlogPost::query()->published()->count()
            + Faq::query()->published()->count()
            + Testimonial::query()->published()->count()
            + TeamMember::query()->published()->count()
            + PageSection::query()->where('is_enabled', true)->count();
        $needsReview = Product::query()->where('is_published', false)->count()
            + PortfolioProject::query()->where('is_published', false)->count()
            + BlogPost::query()->where('status', PublicationStatus::Draft)->count()
            + Faq::query()->where('is_published', false)->count()
            + Testimonial::query()->where('is_published', false)->count()
            + TeamMember::query()->where('is_published', false)->count()
            + PageSection::query()->where('is_enabled', false)->count();
        $missingProductImages = Product::query()->whereNull('image_path')->count();
        $missingProjectImages = PortfolioProject::query()->whereNull('image_path')->count();
        $missingPostImages = BlogPost::query()->whereNull('featured_image_path')->count();
        $missingSectionImages = PageSection::query()
            ->whereIn('section_key', ['hero', 'about'])
            ->whereNull('image_path')
            ->count();
        $missingTeamImages = TeamMember::query()->whereNull('photo_path')->count();
        $missingImages = $missingProductImages + $missingProjectImages + $missingPostImages + $missingSectionImages + $missingTeamImages;
        $recentUpdates = Activity::query()->where('created_at', '>=', now()->subDay())->count();

        $stats = [
            Stat::make('Konten tayang', number_format($published))
                ->description('Item yang saat ini dapat dilihat publik')
                ->descriptionIcon(Heroicon::OutlinedEye)
                ->icon(Heroicon::OutlinedGlobeAlt)
                ->color('success')
                ->url(PageSectionResource::getUrl()),
            Stat::make('Perlu ditinjau', number_format($needsReview))
                ->description($needsReview > 0 ? 'Draf atau bagian yang belum ditayangkan' : 'Semua konten sudah ditinjau')
                ->descriptionIcon($needsReview > 0 ? Heroicon::OutlinedExclamationTriangle : Heroicon::OutlinedCheckCircle)
                ->icon(Heroicon::OutlinedDocumentText)
                ->color($needsReview > 0 ? 'warning' : 'success')
                ->url(BlogPostResource::getUrl()),
            Stat::make('Media belum lengkap', number_format($missingImages))
                ->description($missingImages > 0
                    ? "{$missingProductImages} produk, {$missingProjectImages} proyek, {$missingPostImages} artikel, {$missingSectionImages} bagian, {$missingTeamImages} tim"
                    : 'Gambar utama sudah lengkap')
                ->descriptionIcon(Heroicon::OutlinedPhoto)
                ->icon(Heroicon::OutlinedPhoto)
                ->color($missingImages > 0 ? 'warning' : 'success')
                ->url(ProductResource::getUrl()),
            Stat::make('Aktivitas 24 jam', number_format($recentUpdates))
                ->description('Perubahan konten dan status terbaru')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->icon(Heroicon::OutlinedArrowPath),
        ];

        if (auth()->user()->role->canViewInquiries()) {
            $newInquiries = Inquiry::query()->where('status', InquiryStatus::New)->count();
            $stats[] = Stat::make('Permintaan baru', number_format($newInquiries))
                ->description($newInquiries > 0 ? 'Menunggu tindak lanjut tim' : 'Tidak ada antrean baru')
                ->descriptionIcon(Heroicon::OutlinedEnvelopeOpen)
                ->icon(Heroicon::OutlinedInbox)
                ->url(InquiryResource::getUrl())
                ->color($newInquiries > 0 ? 'warning' : 'success');
        }

        return $stats;
    }
}

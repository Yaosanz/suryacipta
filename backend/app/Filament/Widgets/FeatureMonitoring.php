<?php

namespace App\Filament\Widgets;

use App\Enums\InquiryStatus;
use App\Enums\PublicationStatus;
use App\Filament\Resources\Activities\ActivityResource;
use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\MediaAssets\MediaAssetResource;
use App\Filament\Resources\PageSections\PageSectionResource;
use App\Filament\Resources\PortfolioProjects\PortfolioProjectResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Filament\Resources\Testimonials\TestimonialResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Inquiry;
use App\Models\MediaAsset;
use App\Models\PageSection;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Activitylog\Models\Activity;

class FeatureMonitoring extends StatsOverviewWidget
{
    protected static ?int $sort = 20;

    protected ?string $heading = 'Monitoring seluruh fitur';

    protected ?string $description = 'Buka modul terkait untuk melihat detail, memperbarui data, atau menindaklanjuti item yang membutuhkan perhatian.';

    protected ?string $pollingInterval = '30s';

    protected function getColumns(): array
    {
        return [
            '@md' => 2,
            '@xl' => 3,
            '@2xl' => 4,
            '!@sm' => 2,
            '!@md' => 3,
            '!@xl' => 4,
        ];
    }

    protected function getStats(): array
    {
        $stats = [
            $this->contentStat('Bagian halaman', PageSection::count(), PageSection::where('is_enabled', false)->count(), PageSectionResource::getUrl(), Heroicon::OutlinedHomeModern),
            $this->contentStat('Produk', Product::count(), Product::where('is_published', false)->count(), ProductResource::getUrl(), Heroicon::OutlinedCube),
            $this->contentStat('Portofolio', PortfolioProject::count(), PortfolioProject::where('is_published', false)->count(), PortfolioProjectResource::getUrl(), Heroicon::OutlinedBriefcase),
            $this->contentStat('Artikel', BlogPost::count(), BlogPost::where('status', PublicationStatus::Draft)->count(), BlogPostResource::getUrl(), Heroicon::OutlinedNewspaper),
            $this->contentStat('FAQ', Faq::count(), Faq::where('is_published', false)->count(), FaqResource::getUrl(), Heroicon::OutlinedQuestionMarkCircle),
            $this->contentStat('Testimoni', Testimonial::count(), Testimonial::where('is_published', false)->count(), TestimonialResource::getUrl(), Heroicon::OutlinedChatBubbleLeftRight),
            $this->contentStat('Tim perusahaan', TeamMember::count(), TeamMember::where('is_published', false)->count(), TeamMemberResource::getUrl(), Heroicon::OutlinedUserGroup),
            Stat::make('Pustaka media', number_format(MediaAsset::count()))
                ->description('Aset gambar tersimpan')
                ->icon(Heroicon::OutlinedPhoto)
                ->url(MediaAssetResource::getUrl())
                ->color('info'),
        ];

        if (auth()->user()->role->canViewInquiries()) {
            $open = Inquiry::whereNotIn('status', [InquiryStatus::Closed, InquiryStatus::Spam])->count();
            $stats[] = Stat::make('Prospek penawaran', number_format(Inquiry::count()))
                ->description("{$open} masih perlu ditangani")
                ->icon(Heroicon::OutlinedEnvelopeOpen)
                ->url(InquiryResource::getUrl())
                ->color($open > 0 ? 'warning' : 'success');
        }

        if (auth()->user()->role->canManageSettings()) {
            $stats[] = Stat::make('Profil website', SiteSetting::exists() ? 'Lengkap' : 'Belum diisi')
                ->description('Identitas dan kontak perusahaan')
                ->icon(Heroicon::OutlinedCog6Tooth)
                ->url(SiteSettingResource::getUrl())
                ->color(SiteSetting::exists() ? 'success' : 'warning');
        }

        if (auth()->user()->role->canManageUsers()) {
            $stats[] = Stat::make('Pengguna CMS', number_format(User::where('is_active', true)->count()))
                ->description(User::where('is_active', false)->count().' akun nonaktif')
                ->icon(Heroicon::OutlinedUsers)
                ->url(UserResource::getUrl())
                ->color('info');
            $stats[] = Stat::make('Riwayat aktivitas', number_format(Activity::where('created_at', '>=', now()->subDay())->count()))
                ->description('Perubahan dalam 24 jam')
                ->icon(Heroicon::OutlinedShieldCheck)
                ->url(ActivityResource::getUrl())
                ->color('info');
        }

        return $stats;
    }

    private function contentStat(string $label, int $total, int $drafts, string $url, Heroicon $icon): Stat
    {
        return Stat::make($label, number_format($total))
            ->description($drafts > 0 ? "{$drafts} belum tayang" : 'Seluruh item sudah tayang')
            ->icon($icon)
            ->url($url)
            ->color($drafts > 0 ? 'warning' : 'success');
    }
}

<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\CmsOverview;
use App\Filament\Widgets\ContentStatusChart;
use App\Filament\Widgets\FeatureMonitoring;
use App\Filament\Widgets\InquiryTrendChart;
use App\Filament\Widgets\RecentActivity;
use App\Filament\Widgets\RecentInquiries;
use App\Http\Middleware\AdminSecurityHeaders;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->when(
                config('cms.password_reset_enabled'),
                fn (Panel $configuredPanel): Panel => $configuredPanel->passwordReset(),
            )
            ->profile(EditProfile::class)
            ->multiFactorAuthentication([
                AppAuthentication::make()->recoverable(),
            ], isRequired: true)
            ->strictAuthorization()
            ->brandName('Surya Cipta CMS')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->darkMode()
            ->themeSwitcher()
            ->sidebarCollapsibleOnDesktop()
            ->unsavedChangesAlerts()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                CmsOverview::class,
                FeatureMonitoring::class,
                InquiryTrendChart::class,
                ContentStatusChart::class,
                RecentInquiries::class,
                RecentActivity::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                AdminSecurityHeaders::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

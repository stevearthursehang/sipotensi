<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;
use Filament\Http\Middleware\AuthenticateSession as FilamentAuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents as FilamentDisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\PanelRegistry;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->passwordReset()
            ->brandName('SIPOTENSI SANGIHE — Executive Portal')
            ->font('Plus Jakarta Sans')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('17rem')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn () => new \Illuminate\Support\HtmlString('<link rel="stylesheet" href="' . asset('css/filament-luxury.css') . '">')
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Data Master GIS'),
                NavigationGroup::make()
                    ->label('Investasi'),
                NavigationGroup::make()
                    ->label('Sistem & Akses'),
            ])
            ->navigationItems([
                NavigationItem::make('Lihat Peta Publik')
                    ->icon('heroicon-o-globe-alt')
                    ->url('/map')
                    ->openUrlInNewTab()
                    ->sort(99),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                \App\Filament\Widgets\PotensiStatsWidget::class,
                \App\Filament\Widgets\PotensiSektorChart::class,
                \App\Filament\Widgets\WisataHighlightWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                FilamentAuthenticateSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                FilamentDisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                FilamentAuthenticate::class,
            ])
            ->plugin(FilamentShieldPlugin::make());
    }

    public function boot(): void
    {
        $panel = $this->panel($this->app->make(Panel::class));
        $this->app->make(PanelRegistry::class)->register($panel);
    }
}

<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\View\PanelsRenderHook;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandName('Carpathian CMS')
            ->brandLogo(asset('images/carphatian-logo-transparent.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.ico'))
            ->plugin(
                SpatieLaravelTranslatablePlugin::make()
                    ->defaultLocales(['en', 'ro'])
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn () => view('filament.widgets.language-switcher')
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => '<style>' . file_get_contents(resource_path('css/admin-enhancements.css')) . '</style>'
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn () => '<script>
                    // Auto-save indicator
                    document.addEventListener("livewire:init", () => {
                        Livewire.hook("commit", ({ component, commit, respond }) => {
                            const indicator = document.createElement("div");
                            indicator.className = "auto-save-indicator";
                            indicator.textContent = "✓ Saved";
                            document.body.appendChild(indicator);
                            setTimeout(() => indicator.remove(), 2000);
                        });
                    });
                    
                    // Smooth scroll to validation errors
                    document.addEventListener("DOMContentLoaded", () => {
                        const observer = new MutationObserver(() => {
                            const error = document.querySelector(".fi-fo-field-wrp-error");
                            if (error) {
                                error.scrollIntoView({ behavior: "smooth", block: "center" });
                            }
                        });
                        observer.observe(document.body, { childList: true, subtree: true });
                    });
                </script>'
            )
            ->unsavedChangesAlerts()
            ->databaseNotifications()
            ->databaseNotificationsPolling("30s")
            ->navigationItems([
                NavigationItem::make(__('github_repository'))
                    ->url('https://github.com/msrusu87-web/carpathian-cms', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-code-bracket')
                    ->group(__('external_links'))
                    ->sort(999),
                NavigationItem::make(__('documentation'))
                    ->url('https://github.com/msrusu87-web/carpathian-cms/tree/main/docs', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-book-open')
                    ->group(__('external_links'))
                    ->sort(1000),
                NavigationItem::make(__('live_website'))
                    ->url('https://carphatian.ro', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-globe-alt')
                    ->group(__('external_links'))
                    ->sort(1001),
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()->label('Profil'),
                'logout' => MenuItem::make()->label('Deconectare'),
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->brandName('Carpathian CMS')
            ->brandLogo(asset('images/carphatian-logo-transparent.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.ico'))
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Removed FilamentInfoWidget to hide v3.3.45 branding
            ])
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn () => view('filament.footer')
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

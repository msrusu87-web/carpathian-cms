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
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\ChatConversation;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->authMiddleware([\App\Http\Middleware\FilamentAuthenticate::class])
            
            ->brandName('Carphatian CMS')
            ->brandLogo(asset('images/carphatian-logo-transparent.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/carphatian-logo-transparent.png'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->plugin(
                SpatieLaravelTranslatablePlugin::make()
                    ->defaultLocales(['en', 'ro'])
            )
            ->navigationGroups([
                // Quick Access
                NavigationGroup::make('Quick Links')
                    ->label(__('Quick Links'))
                    ->icon('heroicon-o-bolt')
                    ->collapsed(false),
                
                // Content Management
                NavigationGroup::make('CMS')
                    ->label(__('CMS'))
                    ->icon('heroicon-o-document-text')
                    ->collapsed(),
                NavigationGroup::make('Blog')
                    ->label(__('Blog'))
                    ->icon('heroicon-o-newspaper')
                    ->collapsed(),
                
                // E-commerce
                NavigationGroup::make('Shop')
                    ->label(__('Shop'))
                    ->icon('heroicon-o-shopping-bag')
                    ->collapsed(),
                
                // Design & Media
                NavigationGroup::make('Design')
                    ->label(__('Design'))
                    ->icon('heroicon-o-paint-brush')
                    ->collapsed(),
                NavigationGroup::make('Content')
                    ->label(__('Content'))
                    ->icon('heroicon-o-photo')
                    ->collapsed(),
                
                // Communications
                NavigationGroup::make('Communications')
                    ->label(__('Communications'))
                    ->icon('heroicon-o-envelope')
                    ->collapsed(),
                
                // AI Tools
                NavigationGroup::make('AI')
                    ->label(__('AI'))
                    ->icon('heroicon-o-sparkles')
                    ->collapsed(),
                
                // SEO
                NavigationGroup::make('SEO')
                    ->label(__('SEO'))
                    ->icon('heroicon-o-magnifying-glass')
                    ->collapsed(),
                
                // Users & Permissions
                NavigationGroup::make('Users & Permissions')
                    ->label(__('Users & Permissions'))
                    ->icon('heroicon-o-users')
                    ->collapsed(),
                
                // Settings
                NavigationGroup::make('Settings')
                    ->label(__('Settings'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
            ])
            ->navigationItems([
                NavigationItem::make('View Website')
                    ->url('/', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-globe-alt')
                    ->group('Quick Links')
                    ->sort(1),
                NavigationItem::make('Support Chat')
                    ->url('/admin-chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->group('Communications')
                    ->sort(10)
                    ->badge(function () {
                        try {
                            $count = ChatConversation::whereHas('messages', function ($q) {
                                $q->where('is_admin', false)->where('is_read', false);
                            })->count();
                            return $count ?: null;
                        } catch (\Exception $e) {
                            return null;
                        }
                    }),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->unsavedChangesAlerts()
            ->databaseNotifications()
            ->databaseNotificationsPolling("30s")
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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

<?php

namespace App\Providers\Filament;

use App\Filament\Resources\CampaignResource\Widgets\RecentCampaigns;
use App\Filament\Resources\UserResource\Widgets\UserBalance;
use App\Http\Middleware\AuthCheckMiddleware;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use App\Filament\Resources\CampaignResource\Widgets\CampaignWidget;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use App\Filament\Widgets\TransactionHistory;

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
                'blue' => Color::Blue,
                'indigo' => Color::Indigo,
                'red' => Color::Red,
                'green' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                UserBalance::class,
                CampaignWidget::class,
                TransactionHistory::class,
                RecentCampaigns::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
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
                AuthCheckMiddleware::class,
            ]);
    }
    public function boot()
    {
        Filament::serving(function () {
            if (request()->routeIs('filament.admin.resources.donations.create')) {
                if (!auth()->check()) {
                    echo
                        '<style>
                .fi-sidebar {
                    display: none !important;
                    }
                </style>';
                }
            }
        });
        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
            fn() => view('custom-components.dashboard-buttons'),
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_HEADER_ACTIONS_AFTER,
            fn() => view('custom-components.welcome-text'),
        );

    }

}






















































































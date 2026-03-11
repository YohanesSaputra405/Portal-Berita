<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Portal Berita Admin')
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
            ])
            ->font('Inter')

            /**
             * Inject meta tag berisi user_id & roles untuk digunakan oleh Echo JS
             * serta script listener untuk meneruskan event ke Filament notification
             */
            ->renderHook(
                'panels::body.end',
                function () {
                    if (! Auth::check()) {
                        return '';
                    }

                    $user  = Auth::user();
                    $roles = $user->getRoleNames()->implode(',');

                    return \Illuminate\Support\Facades\Blade::render('
<meta name="auth-user" data-id="{{ $id }}" data-roles="{{ $roles }}">
@vite(["resources/js/filament-notifications.js"])
', ["id" => $user->id, "roles" => $roles]);
                }
            )

            /**
             * Render Livewire notification icon di topbar navbar (hanya untuk reporter)
             */
            ->renderHook(
                'panels::global-search.after',
                function () {
                    if (! Auth::check()) {
                        return '';
                    }

                    $user = Auth::user();

                    if (! $user->hasRole('reporter')) {
                        return '';
                    }

                    return \Illuminate\Support\Facades\Blade::render('
<div class="flex items-center pr-2">
    @livewire(\App\Livewire\ReporterNotifications::class)
</div>
');
                }
            )

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\WelcomeBanner::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\QuickActions::class,
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
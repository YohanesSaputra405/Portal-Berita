<?php

namespace App\Providers;

use Filament\Notifications\Notification;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::listen('post-published', function ($post) {
        Notification::make()
            ->title('Artikel Published!')
            ->body($post['title'])
            ->success()
            ->send();
    });
    }
}

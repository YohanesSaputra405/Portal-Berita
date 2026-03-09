<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('components.public-layout', function ($view) {
            $categories = \Illuminate\Support\Facades\Cache::remember('navbar_categories', 60 * 60, function () {
                return \App\Models\Category::has('posts')->take(15)->get();
            });
            $view->with('categories', $categories);
        });
    }
}

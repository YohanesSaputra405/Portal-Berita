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

            $trending_news = \Illuminate\Support\Facades\Cache::remember('global_trending_news', 60 * 10, function () {
                return \App\Models\Post::published()->trending()->take(4)->get();
            });

            $view->with('categories', $categories);
            $view->with('trending_news', $trending_news);
        });
    }
}

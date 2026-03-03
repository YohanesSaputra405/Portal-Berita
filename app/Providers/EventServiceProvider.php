<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PostPublished;
use App\Listeners\SendPostPublishedNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PostPublished::class => [
            SendPostPublishedNotification::class,
        ],
    ];
}
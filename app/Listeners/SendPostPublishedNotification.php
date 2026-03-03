<?php

namespace App\Listeners;

use App\Events\PostPublished;
use App\Notifications\PostPublishedNotification;

class SendPostPublishedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostPublished $event): void
    {
        $post = $event->post->fresh()->load('author');

        if(!$post || !$post->author) {
            return;
        }

        //Kirim ke pemilik artikel
        $post->author->notify(
            new PostPublishedNotification($post)
        );
    }
}

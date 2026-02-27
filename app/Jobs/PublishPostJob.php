<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\PostHistory;
use App\Enums\PostStatus;
use App\Notifications\PostPublishedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PublishPostJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $postId) {}

    public function handle(): void
    {
        $post = Post::with('author')->find($this->postId);

        if (!$post) {
            return;
        }

        $oldStatus = $post->status;

        $post->update([
            'status' => PostStatus::Published,
            'published_at' => now(),
        ]);

        PostHistory::create([
            'post_id'    => $post->id,
            'actor_id'   => null,
            'old_status' => $oldStatus,
            'new_status' => PostStatus::Published->value,
            'note'       => 'Auto published by system (scheduled)',
        ]);

        $post->author?->notify(
            new PostPublishedNotification($post)
        );
    }
}
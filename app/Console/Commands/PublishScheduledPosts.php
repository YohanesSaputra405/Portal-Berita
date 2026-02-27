<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Enums\PostStatus;
use App\Jobs\PublishPostJob;
use App\Models\PostHistory;
use Illuminate\Support\Carbon;
use App\Notifications\PostPublishedNotification;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';

    protected $description = 'Publish posts that have reached scheduled time';

    public function handle(): void
    {
        $now = Carbon::now();

        $posts = Post::where('status', PostStatus::Finished->value)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->get();
            

        foreach ($posts as $post) {
            $oldStatus = $post->status;
            PublishPostJob::dispatch($post->id);
            $this->info("Post {$post->id} scheduled for publishing.");

            $post->update([
                'status' => PostStatus::Published,
                'published_at' => $now,
            ]);

            // Kirim notifikasi ke author
            $post->author()?->notify(
                new PostPublishedNotification($post)
            );
            
            // Simpan history
            PostHistory::create([
                'post_id'    => $post->id,
                'actor_id'    => null, // system
                'old_status' => $oldStatus->value,
                'new_status' => PostStatus::Published->value,
                'note'       => 'Auto published by system (scheduled)',
            ]);

            $this->info("Post {$post->id} published.");
        }
    }
}
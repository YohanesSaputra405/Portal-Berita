<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostPublished implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    // Private channel khusus untuk reporter pemilik artikel
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('App.Models.User.' . $this->post->user_id);
    }

    // Nama event yang dikirim ke frontend
    public function broadcastAs(): string
    {
        return 'PostPublished';
    }

    // Data yang dikirim ke frontend
    public function broadcastWith(): array
    {
        return [
            'post_id'  => $this->post->id,
            'title'    => $this->post->title,
            'slug'     => $this->post->slug,
            'user_id'  => $this->post->user_id,
            'message'  => "Artikel \"{$this->post->title}\" telah dipublish!",
        ];
    }
}
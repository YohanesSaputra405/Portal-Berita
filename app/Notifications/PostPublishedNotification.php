<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Post;

class PostPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Post $post) {}

    public function via(object $notifiable): array
    {
        return ['database']; // nanti bisa tambah 'mail'
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'post_id' => $this->post->id,
            'title'   => $this->post->title,
            'message' => "Artikel '{$this->post->title}' telah dipublish.",
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Artikel Published')
            ->line("Artikel '{$this->post->title}' telah dipublish.")
            ->action('Lihat Artikel', url('/posts/'.$this->post->slug));
    }
}
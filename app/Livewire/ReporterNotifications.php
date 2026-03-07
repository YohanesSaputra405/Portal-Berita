<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ReporterNotifications extends Component
{
    public bool $open = false;

    /**
     * Refresh badge saat menerima event dari frontend (Echo).
     */
    #[On('reporter-notification-received')]
    public function refreshNotifications(): void
    {
        // Livewire akan re-render otomatis karena properti computed
    }

    #[On('show-toast-notification')]
    public function showToastNotification($title, $message): void
    {
        \Filament\Notifications\Notification::make()
            ->success()
            ->title($title)
            ->body($message)
            ->send();
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function markAllRead(): void
    {
        Auth::user()?->unreadNotifications()->update(['read_at' => now()]);
        $this->open = false;
    }

    public function getUnreadCountProperty(): int
    {
        return Auth::user()?->unreadNotifications()->count() ?? 0;
    }

    public function getNotificationsProperty()
    {
        return Auth::user()
            ?->notifications()
            ->latest()
            ->limit(10)
            ->get() ?? collect();
    }

    public function render()
    {
        $user = Auth::user();

        // Hanya tampil untuk reporter
        if (! $user || ! $user->hasRole('reporter')) {
            return <<<'HTML'
                <div></div>
            HTML;
        }

        return view('livewire.reporter-notifications', [
            'unreadCount'   => $this->unreadCount,
            'notifications' => $this->notifications,
        ]);
    }
}

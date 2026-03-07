<div class="relative flex items-center">
    <x-filament::dropdown placement="bottom-end" width="sm">
        <x-slot name="trigger">
            <x-filament::icon-button
                icon="heroicon-o-bell"
                color="gray"
                size="lg"
                label="Notifikasi Saya"
                class="relative"
            >
                @if($unreadCount > 0)
                    <x-slot name="badge">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </x-slot>
                @endif
            </x-filament::icon-button>
        </x-slot>

        <div class="px-4 py-3 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
            <span class="text-sm font-semibold text-gray-950 dark:text-white">Notifikasi (<span wire:poll.10s>{{ $unreadCount }}</span>)</span>
            
            @if($unreadCount > 0)
                <x-filament::button size="xs" color="gray" wire:click="markAllRead">
                    Tandai dibaca
                </x-filament::button>
            @endif
        </div>

        <div class="max-h-72 overflow-y-auto w-80">
            @forelse($notifications as $notif)
                @php
                    $isRead  = ! is_null($notif->read_at);
                @endphp
                <div class="px-4 py-3 border-b border-gray-100 dark:border-white/5 {{ $isRead ? 'opacity-60' : 'bg-primary-50 dark:bg-primary-400/10' }}">
                    <div class="flex justify-between items-start gap-2">
                        <p class="text-sm font-medium text-gray-950 dark:text-white truncate">
                            {{ $notif->data['title'] ?? 'Artikel' }}
                        </p>
                        @if(!$isRead)
                            <div class="w-2 h-2 rounded-full bg-primary-600 dark:bg-primary-500 shrink-0 mt-1.5"></div>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                        {{ $notif->data['message'] ?? 'Artikel Anda telah dipublish.' }}
                    </p>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1.5">
                        {{ $notif->created_at->diffForHumans() }}
                    </p>
                </div>
            @empty
                <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                    Belum ada notifikasi.
                </div>
            @endforelse
        </div>
    </x-filament::dropdown>
</div>

<script>
    document.addEventListener('livewire:init', function () {
        window.addEventListener('echo-reporter-notification', function () {
            Livewire.dispatch('reporter-notification-received')
        })
    })
</script>

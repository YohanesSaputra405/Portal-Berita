import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
})

window.addEventListener("notify", function (event) {
    if (typeof window.Livewire !== "undefined") {
        window.Livewire.dispatch("show-toast-notification", {
            title: event.detail.title,
            message: event.detail.body
        });
    }
});

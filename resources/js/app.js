import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';


window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

window.Echo.channel('posts')
    .listen('.post.published', (e) => {

        window.dispatchEvent(new CustomEvent('post-published', {
            detail: {
                title: 'Artikel Published',
                body: e.post.title
            }
        }));
    });
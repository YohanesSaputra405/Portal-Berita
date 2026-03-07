import './bootstrap'

function registerEcho() {

    if (!window.Echo) {
        console.log('Echo not ready')
        return
    }

    // Ambil user_id dari meta tag yang di-inject oleh AdminPanelProvider
    const meta = document.querySelector('meta[name="auth-user"]')
    if (!meta) {
        return
    }

    const userId = meta.getAttribute('data-id')
    const userRoles = (meta.getAttribute('data-roles') || '').split(',')
    const isReporter = userRoles.includes('reporter')

    if (!userId || !isReporter) {
        return
    }

    console.log('Binding Echo for reporter user:', userId)

    // Private channel khusus untuk user ini
    window.Echo.private(`App.Models.User.${userId}`)
        .stopListening('.PostPublished')
        .listen('.PostPublished', (e) => {

            console.log('Realtime PostPublished event:', e)

            // Tampilkan toast via Filament notification sistem
            window.dispatchEvent(
                new CustomEvent('notify', {
                    detail: {
                        status: 'success',
                        title: '🎉 Artikel Dipublish!',
                        body: e.message || `Artikel "${e.title}" telah dipublish.`,
                    },
                })
            )

            // Trigger refresh badge Livewire component
            window.dispatchEvent(new CustomEvent('echo-reporter-notification'))
        })
}

document.addEventListener('livewire:init', registerEcho)
document.addEventListener('livewire:navigated', registerEcho)

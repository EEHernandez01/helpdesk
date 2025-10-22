import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Echo (opcional: Pusher)
import Echo from 'laravel-echo';

try {
    // Laravel Reverb (WebSockets local). Usa las mismas claves que PUSHER_* para compatibilidad
    const appKey = document.querySelector('meta[name="pusher-key"]')?.content || 'local-key';
    const wsHost = document.querySelector('meta[name="reverb-host"]')?.content || window.location.hostname;
    const wsPort = Number(document.querySelector('meta[name="reverb-port"]')?.content || 6001);

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: appKey,
        wsHost,
        wsPort,
        forceTLS: false,
    });

    const userId = document.querySelector('meta[name="user-id"]')?.content;
    if (userId && window.Echo) {
        window.Echo.private(`users.${userId}`)
            .notification((notification) => {
                const badge = document.querySelector('button .bg-red-600');
                if (badge) {
                    const n = parseInt(badge.textContent.trim() || '0', 10) || 0;
                    badge.textContent = String(n + 1);
                }
            });
    }
} catch (e) {
    console.warn('Echo init error:', e);
}

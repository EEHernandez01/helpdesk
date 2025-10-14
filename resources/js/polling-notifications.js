// Polling para notificaciones en entornos sin websockets (hosting compartido)
(function () {
    const POLL_INTERVAL = 15000; // 15 segundos
    const pollUrl = document.querySelector('meta[name="notifications-poll-url"]')?.getAttribute('content') || '/notifications/poll';

    async function fetchNotifications() {
        try {
            const res = await fetch(pollUrl, { credentials: 'same-origin' });
            if (!res.ok) return;
            const data = await res.json();
            updateNotificationsUI(data);
        } catch (e) {
            console.error('Error polling notifications:', e);
        }
    }

    function updateNotificationsUI(data) {
        if (!data) return;
        // Actualizar contador
        const badge = document.querySelector('#notification-badge');
        if (badge) {
            badge.textContent = data.unread > 0 ? data.unread : '';
            badge.style.display = data.unread > 0 ? 'inline-block' : 'none';
        }

        // Actualizar dropdown (si existe)
        const dropdownList = document.querySelector('#notification-dropdown-list');
        if (dropdownList && Array.isArray(data.notifications)) {
            dropdownList.innerHTML = '';
            data.notifications.slice(0, 10).forEach(n => {
                const li = document.createElement('li');
                li.className = 'px-4 py-2 hover:bg-gray-50 flex items-start gap-3';
                const title = (n.data && (n.data.title || n.data.message)) || n.type;
                const short = document.createElement('div');
                short.innerHTML = `<div class="font-semibold text-sm text-gray-800">${escapeHtml(title)}</div><div class="text-xs text-gray-500">${escapeHtml(n.created_at)}</div>`;
                const a = document.createElement('a');
                a.href = '/notifications/' + encodeURIComponent(n.id) + '/go';
                a.appendChild(short);
                li.appendChild(a);
                dropdownList.appendChild(li);
            });
        }
    }

    function escapeHtml(unsafe) {
        if (unsafe === null || unsafe === undefined) return '';
        return String(unsafe)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Iniciar polling solo si existe el badge o dropdown
    if (document.querySelector('#notification-badge') || document.querySelector('#notification-dropdown-list')) {
        fetchNotifications();
        setInterval(fetchNotifications, POLL_INTERVAL);
    }
})();

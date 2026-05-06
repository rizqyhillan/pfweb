import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Setup Notification Listener globally
// Uses .listen() for custom event (same pattern as ProductStockUpdated)
// instead of .notification() which relies on broken queue-based broadcast
function setupNotificationListener() {
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    if (!userIdMeta || !window.Echo) return;

    const userId = userIdMeta.getAttribute('content');
    console.log('[PawPet] Setting up notification listener for user:', userId);

    window.Echo.private('App.Models.User.' + userId)
        .listen('.new-notification', (data) => {
            console.log('[PawPet] Real-time notification received:', data);

            // Add to top of notification dropdown list
            const list = document.getElementById('notification-list');
            const empty = document.getElementById('empty-notif');
            if (empty) empty.remove();

            if (list) {
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action dropdown-notifications-item py-3 px-4 border-bottom';
                li.id = 'notif-live-' + Date.now();
                
                let icon = 'bx-info-circle';
                if(data.type == 'success') icon = 'bx-check-circle';
                if(data.type == 'warning') icon = 'bx-error';
                if(data.type == 'danger') icon = 'bx-x-circle';

                li.innerHTML = `
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded-circle bg-label-${data.type || 'primary'}"><i class="bx ${icon}"></i></span>
                            </div>
                        </div>
                        <a href="${data.url || '#'}" class="flex-grow-1 text-decoration-none text-body" style="min-width: 0;" ${data.url ? 'onclick="window.markNotificationAsRead(\\'live-' + Date.now() + '\\')"' : ''}>
                            <h6 class="mb-1 text-truncate fw-bold">${data.title || 'Info'}
                                <span class="badge badge-dot bg-primary ms-1"></span>
                            </h6>
                            <p class="mb-1 text-muted" style="font-size: 0.85rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">${data.message || ''}</p>
                            <small class="text-muted d-block" style="font-size: 0.75rem;"><i class="bx bx-time-five me-1"></i>Baru saja</small>
                        </a>
                        <div class="flex-shrink-0 dropdown-notifications-actions ms-2 d-flex flex-column align-items-end">
                            <a href="javascript:void(0)" class="text-muted dropdown-notifications-archive" onclick="this.closest('li').remove()" data-bs-toggle="tooltip" title="Tandai dibaca">
                                <i class="bx bx-check fs-4"></i>
                            </a>
                        </div>
                    </div>
                `;
                list.insertBefore(li, list.firstChild);
            }

            // Update badge count
            if (typeof window.updateNotificationCount === 'function') {
                window.updateNotificationCount(1);
            }

            // Show Toast pop-up
            if (window.PawPetRealtime && typeof window.PawPetRealtime.showToast === 'function') {
                window.PawPetRealtime.showToast(
                    data.title || 'Notifikasi Baru',
                    data.message || '',
                    data.type || 'info',
                    data.url || '#'
                );
            }

            // Animate Bell icon
            const bellIcon = document.querySelector('.dropdown-notifications > a > i.bx-bell');
            if (bellIcon) {
                bellIcon.classList.add('bx-tada');
                setTimeout(() => bellIcon.classList.remove('bx-tada'), 2000);
            }
        });
}

// Run immediately since @vite is at the bottom of body
setupNotificationListener();

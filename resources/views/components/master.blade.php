<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="user-id" content="{{ auth()->id() }}">
    @endauth
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    <style>
        /* Toast notifications container */
        .toast-container-custom {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            max-width: 380px;
        }
        .toast-custom {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            border-left: 4px solid #0d6efd;
            padding: 16px;
            margin-bottom: 12px;
            animation: slideIn 0.3s ease-out;
        }
        .toast-custom.toast-message { border-left-color: #0d6efd; }
        .toast-custom.toast-friend { border-left-color: #198754; }
        .toast-custom.toast-application { border-left-color: #fd7e14; }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        /* Online indicator dot */
        .online-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
            position: absolute;
            bottom: 2px;
            right: 2px;
        }
        .online-indicator.online { background-color: #198754; }
        .online-indicator.offline { background-color: #adb5bd; }
    </style>
</head>
<body>
    <x-navbar/>
    
    <!-- Toast notification container -->
    <div class="toast-container-custom" id="toast-container"></div>

    <main>
        {{ $slot }}
    </main>
    @yield('footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userId = Number(document.querySelector('meta[name="user-id"]')?.content || 0);

            window.updateNotificationBadge = updateNotificationBadge;
            window.updateMessageBadge = updateMessageBadge;
            window.getRealtimeSocketId = getRealtimeSocketId;

            if (!userId) {
                return;
            }

            updateNotificationBadge();
            updateMessageBadge();
            setInterval(updateNotificationBadge, 8000);
            setInterval(updateMessageBadge, 8000);

            if (typeof window.Echo === 'undefined') {
                return;
            }

            window.Echo.private('notifications.' + userId)
                .listen('.new.notification', (payload) => {
                    const notificationPayload = payload || {};
                    const data = notificationPayload.data || {};

                    if (!data.silent && data.message) {
                        showToast(data.message, notificationPayload.type);
                    }

                    updateNotificationBadge();
                    updateMessageBadge();

                    window.dispatchEvent(new CustomEvent('talentia:notification', {
                        detail: notificationPayload,
                    }));
                });

            subscribeToPresenceChannel();
        });

        function getRealtimeSocketId() {
            if (typeof window.Echo === 'undefined' || typeof window.Echo.socketId !== 'function') {
                return null;
            }

            return window.Echo.socketId() || null;
        }

        function subscribeToPresenceChannel() {
            if (typeof window.Echo === 'undefined') {
                return;
            }

            if (typeof window.Echo.join === 'function') {
                window.Echo.join('online')
                    .here((users) => {
                        (users || []).forEach((presenceUser) => {
                            applyUserStatusChange({
                                user_id: Number(presenceUser.id),
                                is_online: true,
                                last_seen_at: null,
                            });
                        });
                    })
                    .joining((presenceUser) => {
                        applyUserStatusChange({
                            user_id: Number(presenceUser.id),
                            is_online: true,
                            last_seen_at: null,
                        });
                    })
                    .leaving((presenceUser) => {
                        applyUserStatusChange({
                            user_id: Number(presenceUser.id),
                            is_online: false,
                            last_seen_at: new Date().toISOString(),
                        });
                    })
                    .listen('.user.status', applyUserStatusChange);

                return;
            }

            window.Echo.channel('online').listen('.user.status', applyUserStatusChange);
        }

        function applyUserStatusChange(payload) {
            const userId = Number(payload?.user_id);

            if (!userId) {
                return;
            }

            const isOnline = Boolean(payload?.is_online);
            const selectors = [
                '.online-indicator[data-user-id="' + userId + '"]',
                '[data-user-id="' + userId + '"] .online-indicator',
                '[data-user-id="' + userId + '"].online-indicator',
            ];

            document.querySelectorAll(selectors.join(', ')).forEach((indicator) => {
                indicator.classList.toggle('online', isOnline);
                indicator.classList.toggle('offline', !isOnline);
            });

            window.dispatchEvent(new CustomEvent('talentia:user-status-changed', {
                detail: {
                    user_id: userId,
                    is_online: isOnline,
                    last_seen_at: payload?.last_seen_at || null,
                },
            }));
        }

        function showToast(message, type = 'message') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const typeClass = type === 'friend_request' || type === 'friend_accepted' 
                ? 'toast-friend' 
                : type === 'application_status' 
                    ? 'toast-application' 
                    : 'toast-message';

            const iconMap = {
                'new_message': 'bi-chat-dots-fill',
                'friend_request': 'bi-person-plus-fill',
                'friend_accepted': 'bi-person-check-fill',
                'application_status': 'bi-briefcase-fill',
            };
            const icon = iconMap[type] || 'bi-bell-fill';

            const toast = document.createElement('div');
            toast.className = 'toast-custom ' + typeClass;
            toast.innerHTML = `
                <div class="d-flex align-items-start">
                    <i class="bi ${icon} me-2 mt-1 text-primary"></i>
                    <div class="flex-grow-1">
                        <p class="mb-0 small fw-medium">${message}</p>
                    </div>
                    <button class="btn-close btn-close-sm ms-2" onclick="this.closest('.toast-custom').remove()"></button>
                </div>
            `;
            container.appendChild(toast);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.style.animation = 'slideOut 0.3s ease-in';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        }

        function updateNotificationBadge() {
            fetch('/notifications/unread-count', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    }
                })
                .catch(() => {});
        }

        function updateMessageBadge() {
            fetch('/messages/unread-count', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('message-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    }
                })
                .catch(() => {});
        }
    </script>
    @endauth

    @yield('scripts')
</body>
</html>

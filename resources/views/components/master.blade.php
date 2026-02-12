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
        // Real-time notification system
        document.addEventListener('DOMContentLoaded', function() {
            const userId = document.querySelector('meta[name="user-id"]')?.content;
            if (!userId || typeof window.Echo === 'undefined') return;

            // Listen for notifications on private channel
            window.Echo.private('notifications.' + userId)
                .listen('.new.notification', (data) => {
                    // Show toast
                    showToast(data.data.message, data.type);
                    // Update notification badge
                    updateNotificationBadge();
                    // Update message badge if it's a message notification
                    if (data.type === 'new_message') {
                        updateMessageBadge();
                    }
                });

            // Listen for user status changes
            window.Echo.channel('online')
                .listen('.user.status', (data) => {
                    // Update all online indicators for this user
                    document.querySelectorAll('[data-user-id="' + data.user_id + '"] .online-indicator')
                        .forEach(el => {
                            el.classList.toggle('online', data.is_online);
                            el.classList.toggle('offline', !data.is_online);
                        });
                });
        });

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
            fetch('/notifications/unread-count')
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    }
                });
        }

        function updateMessageBadge() {
            fetch('/messages/unread-count')
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('message-badge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    }
                });
        }
    </script>
    @endauth

    @yield('scripts')
</body>
</html>
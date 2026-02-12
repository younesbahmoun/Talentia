<x-master title="Notifications">
    <div class="container my-5">
        <h2 class="fw-bold mb-4">
            <i class="bi bi-bell text-primary"></i> Notifications
        </h2>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="list-group list-group-flush" id="notifications-list">
                    @forelse($notifications as $notification)
                        <div class="list-group-item d-flex align-items-center px-0 py-3 {{ $notification->read_at ? '' : 'bg-light' }}">
                            <div class="me-3 text-primary">
                                @php
                                    $type = $notification->data['type'] ?? 'default';
                                    $iconMap = [
                                        'friend_request' => 'bi-person-plus-fill text-success',
                                        'new_message' => 'bi-chat-dots-fill text-primary',
                                        'application_status' => 'bi-briefcase-fill text-warning',
                                        'default' => 'bi-bell-fill text-primary',
                                    ];
                                    $icon = $iconMap[$type] ?? $iconMap['default'];
                                @endphp
                                <i class="bi {{ $icon }} fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 fw-medium">
                                    {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                                </p>
                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div>
                                @if($type === 'friend_request' && isset($notification->data['sender_id']))
                                    <a href="{{ route('profile.detail', $notification->data['sender_id']) }}" class="btn btn-sm btn-outline-primary">
                                        Voir le profil
                                    </a>
                                @elseif($type === 'new_message' && isset($notification->data['conversation_id']))
                                    <a href="{{ route('conversations.show', $notification->data['conversation_id']) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-chat me-1"></i>Voir
                                    </a>
                                @elseif($type === 'application_status' && isset($notification->data['offre_id']))
                                    <a href="{{ route('offres.detail', $notification->data['offre_id']) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-briefcase me-1"></i>Voir l'offre
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-bell-slash fs-1 d-block mb-3"></i>
                            Aucune notification pour le moment.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationsList = document.getElementById('notifications-list');
            if (!notificationsList) {
                return;
            }

            window.addEventListener('talentia:notification', (event) => {
                const payload = event.detail || {};
                const data = payload.data || {};
                const type = payload.type || data.type || null;

                if (!type || !data.message || data.silent) {
                    return;
                }

                const emptyState = notificationsList.querySelector('.text-center.py-5.text-muted');
                if (emptyState) {
                    emptyState.remove();
                }

                const item = document.createElement('div');
                item.className = 'list-group-item d-flex align-items-center px-0 py-3 bg-light';

                const iconClass = getIconClass(type);
                item.innerHTML =
                    '<div class="me-3 text-primary">' +
                        '<i class="bi ' + iconClass + ' fs-4"></i>' +
                    '</div>' +
                    '<div class="flex-grow-1">' +
                        '<p class="mb-1 fw-medium">' + escapeHtml(data.message) + '</p>' +
                        '<small class="text-muted">A l\'instant</small>' +
                    '</div>' +
                    '<div>' + buildActionButton(type, data) + '</div>';

                notificationsList.prepend(item);

                if (typeof window.updateNotificationBadge === 'function') {
                    window.updateNotificationBadge();
                }
            });

            function getIconClass(type) {
                const iconMap = {
                    friend_request: 'bi-person-plus-fill text-success',
                    new_message: 'bi-chat-dots-fill text-primary',
                    application_status: 'bi-briefcase-fill text-warning',
                };

                return iconMap[type] || 'bi-bell-fill text-primary';
            }

            function buildActionButton(type, data) {
                if (type === 'friend_request' && data.sender_id) {
                    return '<a href="/profiles/' + Number(data.sender_id) + '" class="btn btn-sm btn-outline-primary">Voir le profil</a>';
                }

                if (type === 'new_message' && data.conversation_id) {
                    return '<a href="/conversations/' + Number(data.conversation_id) + '" class="btn btn-sm btn-outline-primary"><i class="bi bi-chat me-1"></i>Voir</a>';
                }

                if (type === 'application_status' && data.offre_id) {
                    return '<a href="/offres/detail/' + Number(data.offre_id) + '" class="btn btn-sm btn-outline-warning"><i class="bi bi-briefcase me-1"></i>Voir l\'offre</a>';
                }

                return '';
            }

            function escapeHtml(value) {
                const div = document.createElement('div');
                div.textContent = value || '';
                return div.innerHTML;
            }
        });
    </script>
    @endsection
</x-master>

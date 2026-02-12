<x-master title="Messages">
    @section('styles')
        <style>
            .conversation-list {
                background: white;
                border-radius: 12px;
                overflow: hidden;
            }
            .conversation-item {
                display: flex;
                align-items: center;
                padding: 16px 20px;
                border-bottom: 1px solid #f0f0f0;
                text-decoration: none;
                color: inherit;
                transition: background 0.15s;
            }
            .conversation-item:hover {
                background: #f8f9fa;
                color: inherit;
            }
            .conversation-item.unread {
                background: #f0f4ff;
            }
            .conversation-avatar {
                position: relative;
                flex-shrink: 0;
            }
            .conversation-avatar img {
                width: 52px;
                height: 52px;
                border-radius: 50%;
                object-fit: cover;
            }
            .conversation-info {
                flex-grow: 1;
                min-width: 0;
                margin-left: 14px;
            }
            .conversation-name {
                font-weight: 600;
                font-size: 15px;
                margin-bottom: 2px;
            }
            .conversation-preview {
                color: #6c757d;
                font-size: 13px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 300px;
            }
            .conversation-meta {
                text-align: right;
                flex-shrink: 0;
                margin-left: 12px;
            }
            .conversation-time {
                font-size: 12px;
                color: #adb5bd;
            }
            .unread-badge {
                background: #0d6efd;
                color: white;
                border-radius: 50%;
                width: 22px;
                height: 22px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                font-weight: 700;
                margin-top: 4px;
                margin-left: auto;
            }
            .empty-state {
                text-align: center;
                padding: 60px 20px;
            }
            .empty-state i {
                font-size: 48px;
                color: #dee2e6;
                margin-bottom: 16px;
            }
        </style>
    @endsection

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="bi bi-chat-dots text-primary me-2"></i>Messages
            </h4>
        </div>

        <div class="conversation-list shadow-sm">
            @forelse($conversations as $conv)
                <a href="{{ route('conversations.show', $conv->id) }}" 
                   class="conversation-item {{ $conv->unread_count > 0 ? 'unread' : '' }}"
                   data-conversation-id="{{ $conv->id }}"
                   data-latest-message-id="{{ $conv->latestMessage?->id ?? 0 }}">
                    <div class="conversation-avatar" data-user-id="{{ $conv->other_user->id }}">
                        <img src="{{ $conv->other_user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($conv->other_user->name) }}" 
                             alt="{{ $conv->other_user->name }}">
                        <span class="online-indicator {{ $conv->other_user->is_online ? 'online' : 'offline' }}"></span>
                    </div>
                    <div class="conversation-info">
                        <div class="conversation-name">
                            {{ $conv->other_user->name }} {{ $conv->other_user->prenom ?? '' }}
                        </div>
                        <div class="conversation-preview">
                            @if($conv->latestMessage)
                                @if($conv->latestMessage->sender_id === auth()->id())
                                    <span class="text-muted">Vous: </span>
                                @endif
                                @if($conv->latestMessage->hasFile())
                                    <i class="bi bi-paperclip"></i> 
                                    {{ $conv->latestMessage->file_name ?? 'Fichier' }}
                                @else
                                    {{ Str::limit($conv->latestMessage->body, 50) }}
                                @endif
                            @else
                                <span class="text-muted fst-italic">Démarrer la conversation...</span>
                            @endif
                        </div>
                    </div>
                    <div class="conversation-meta">
                        @if($conv->latestMessage)
                            <div class="conversation-time">
                                {{ $conv->latestMessage->created_at->diffForHumans(null, true) }}
                            </div>
                        @endif
                        @if($conv->unread_count > 0)
                            <div class="unread-badge">{{ $conv->unread_count }}</div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <i class="bi bi-chat-square-dots d-block"></i>
                    <h5 class="fw-bold text-muted">Aucune conversation</h5>
                    <p class="text-muted small">Allez dans votre réseau pour démarrer une conversation avec un ami.</p>
                    <a href="{{ route('network') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-people me-1"></i> Mon réseau
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const conversationList = document.querySelector('.conversation-list');
            const currentUserId = Number(document.querySelector('meta[name="user-id"]')?.content || 0);

            if (!conversationList || !currentUserId) {
                return;
            }

            const subscribedConversations = new Set();
            const conversationItems = conversationList.querySelectorAll('.conversation-item[data-conversation-id]');
            const latestMessageByConversation = new Map();

            conversationItems.forEach((item) => {
                const conversationId = Number(item.dataset.conversationId || 0);
                const latestMessageId = Number(item.dataset.latestMessageId || 0);

                if (conversationId) {
                    latestMessageByConversation.set(conversationId, latestMessageId);
                }

                if (!conversationId || typeof window.Echo === 'undefined' || subscribedConversations.has(conversationId)) {
                    return;
                }

                subscribedConversations.add(conversationId);

                window.Echo.private('conversation.' + conversationId)
                    .listen('.message.sent', handleMessageSent)
                    .listen('.message.read', handleMessageRead);
            });

            pollAllConversations();
            setInterval(pollAllConversations, 4000);

            function handleMessageSent(payload) {
                const conversationId = Number(payload?.conversation_id || 0);
                if (!conversationId) {
                    return;
                }

                const payloadMessageId = Number(payload?.id || 0);
                const currentLatestMessageId = Number(latestMessageByConversation.get(conversationId) || 0);

                if (payloadMessageId && payloadMessageId <= currentLatestMessageId) {
                    return;
                }

                const item = conversationList.querySelector('[data-conversation-id="' + conversationId + '"]');
                if (!item) {
                    return;
                }

                const sentByCurrentUser = Number(payload.sender_id) === currentUserId;
                updateConversationPreview(item, payload, sentByCurrentUser);
                updateConversationTime(item, payload.created_at);
                latestMessageByConversation.set(conversationId, payloadMessageId || currentLatestMessageId);
                item.dataset.latestMessageId = String(payloadMessageId || currentLatestMessageId);

                if (!sentByCurrentUser) {
                    const nextUnreadCount = getUnreadCount(item) + 1;
                    setUnreadCount(item, nextUnreadCount);
                }

                conversationList.prepend(item);

                if (typeof window.updateMessageBadge === 'function') {
                    window.updateMessageBadge();
                }
            }

            function handleMessageRead(payload) {
                const readerId = Number(payload?.reader_id || 0);
                const conversationId = Number(payload?.conversation_id || 0);

                if (!conversationId || readerId !== currentUserId) {
                    return;
                }

                const item = conversationList.querySelector('[data-conversation-id="' + conversationId + '"]');
                if (!item) {
                    return;
                }

                setUnreadCount(item, 0);

                if (typeof window.updateMessageBadge === 'function') {
                    window.updateMessageBadge();
                }
            }

            function pollAllConversations() {
                latestMessageByConversation.forEach((latestMessageId, conversationId) => {
                    fetch('/messages/' + conversationId + '/latest?after_id=' + latestMessageId, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                    })
                        .then(parseJsonResponse)
                        .then((data) => {
                            if (!Array.isArray(data.messages) || data.messages.length === 0) {
                                return;
                            }

                            data.messages.forEach((message) => {
                                handleMessageSent(message);
                            });
                        })
                        .catch(() => {});
                });
            }

            function parseJsonResponse(response) {
                return response.text().then((rawBody) => {
                    let parsedBody = null;

                    try {
                        parsedBody = rawBody ? JSON.parse(rawBody) : null;
                    } catch (error) {
                        parsedBody = null;
                    }

                    if (!response.ok || !parsedBody) {
                        throw new Error('Invalid JSON response from server.');
                    }

                    return parsedBody;
                });
            }

            function updateConversationPreview(item, payload, sentByCurrentUser) {
                const preview = item.querySelector('.conversation-preview');
                if (!preview) {
                    return;
                }

                let previewContent = '';

                if (payload.file_path) {
                    const fileName = truncate(payload.file_name || 'Fichier', 50);
                    previewContent = '<i class="bi bi-paperclip"></i> ' + escapeHtml(fileName);
                } else if (payload.body) {
                    previewContent = escapeHtml(truncate(payload.body, 50));
                } else {
                    previewContent = '<span class="text-muted fst-italic">Nouveau message</span>';
                }

                if (sentByCurrentUser) {
                    previewContent = '<span class="text-muted">Vous: </span>' + previewContent;
                }

                preview.innerHTML = previewContent;
            }

            function updateConversationTime(item, createdAt) {
                const meta = item.querySelector('.conversation-meta');
                if (!meta) {
                    return;
                }

                let timeEl = meta.querySelector('.conversation-time');
                if (!timeEl) {
                    timeEl = document.createElement('div');
                    timeEl.className = 'conversation-time';
                    meta.prepend(timeEl);
                }

                timeEl.textContent = "a l'instant";

                if (createdAt) {
                    const date = new Date(createdAt);
                    if (!Number.isNaN(date.getTime())) {
                        timeEl.title = date.toLocaleString('fr-FR');
                    }
                }
            }

            function getUnreadCount(item) {
                const badge = item.querySelector('.unread-badge');
                if (!badge) {
                    return 0;
                }

                return Number(badge.textContent || 0);
            }

            function setUnreadCount(item, count) {
                const meta = item.querySelector('.conversation-meta');
                if (!meta) {
                    return;
                }

                let badge = meta.querySelector('.unread-badge');

                if (count <= 0) {
                    item.classList.remove('unread');
                    if (badge) {
                        badge.remove();
                    }
                    return;
                }

                item.classList.add('unread');

                if (!badge) {
                    badge = document.createElement('div');
                    badge.className = 'unread-badge';
                    meta.appendChild(badge);
                }

                badge.textContent = String(count);
            }

            function truncate(value, maxLength) {
                if (!value || value.length <= maxLength) {
                    return value || '';
                }

                return value.slice(0, maxLength - 3) + '...';
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

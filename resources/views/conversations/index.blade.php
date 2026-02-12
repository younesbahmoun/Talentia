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
                   data-conversation-id="{{ $conv->id }}">
                    <div class="conversation-avatar">
                        <img src="{{ $conv->other_user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($conv->other_user->name) }}" 
                             alt="{{ $conv->other_user->name }}">
                        <span class="online-indicator {{ $conv->other_user->is_online ? 'online' : 'offline' }}" 
                              data-user-id="{{ $conv->other_user->id }}"></span>
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
</x-master>

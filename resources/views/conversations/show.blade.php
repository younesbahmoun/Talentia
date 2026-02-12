<x-master title="Chat - {{ $otherUser->name }}">
    @section('styles')
        <style>
            .chat-container {
                display: flex;
                flex-direction: column;
                height: calc(100vh - 120px);
                background: white;
                border-radius: 12px;
                overflow: hidden;
            }
            .chat-header {
                display: flex;
                align-items: center;
                padding: 16px 20px;
                border-bottom: 1px solid #e9ecef;
                background: #f8f9fa;
            }
            .chat-header-avatar {
                position: relative;
                margin-right: 12px;
            }
            .chat-header-avatar img {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                object-fit: cover;
            }
            .chat-header-info h6 {
                margin: 0;
                font-weight: 600;
            }
            .chat-header-info small {
                color: #6c757d;
            }
            .chat-messages {
                flex: 1;
                overflow-y: auto;
                padding: 20px;
                display: flex;
                flex-direction: column;
                gap: 8px;
                background: #f0f2f5;
            }
            .message-bubble {
                max-width: 70%;
                padding: 10px 16px;
                border-radius: 18px;
                word-wrap: break-word;
                position: relative;
            }
            .message-sent {
                align-self: flex-end;
                background: #0d6efd;
                color: white;
                border-bottom-right-radius: 4px;
            }
            .message-received {
                align-self: flex-start;
                background: white;
                color: #212529;
                border-bottom-left-radius: 4px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.08);
            }
            .message-text {
                font-size: 14px;
                line-height: 1.4;
                margin: 0;
            }
            .message-time {
                font-size: 11px;
                opacity: 0.7;
                margin-top: 4px;
                text-align: right;
            }
            .message-file {
                margin-top: 6px;
            }
            .message-file img {
                max-width: 240px;
                border-radius: 8px;
                cursor: pointer;
            }
            .message-file-doc {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                background: rgba(255,255,255,0.15);
                border-radius: 8px;
                text-decoration: none;
                color: inherit;
            }
            .message-sent .message-file-doc {
                background: rgba(255,255,255,0.2);
                color: white;
            }
            .message-received .message-file-doc {
                background: #f0f2f5;
                color: #212529;
            }
            .chat-input-container {
                padding: 16px 20px;
                border-top: 1px solid #e9ecef;
                background: white;
            }
            .chat-input-form {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .chat-input {
                flex: 1;
                border: 1px solid #dee2e6;
                border-radius: 24px;
                padding: 10px 18px;
                font-size: 14px;
                outline: none;
                transition: border-color 0.2s;
            }
            .chat-input:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
            }
            .chat-send-btn {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                border: none;
                background: #0d6efd;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: background 0.2s;
            }
            .chat-send-btn:hover { background: #0b5ed7; }
            .chat-file-btn {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                border: 1px solid #dee2e6;
                background: white;
                color: #6c757d;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s;
            }
            .chat-file-btn:hover {
                background: #f8f9fa;
                border-color: #0d6efd;
                color: #0d6efd;
            }
            .date-separator {
                text-align: center;
                padding: 8px 0;
            }
            .date-separator span {
                background: #e9ecef;
                padding: 4px 14px;
                border-radius: 20px;
                font-size: 12px;
                color: #6c757d;
                font-weight: 500;
            }
            .file-preview-overlay {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.4);
                z-index: 9998;
                display: none;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .file-preview-card {
                background: white;
                border-radius: 12px;
                padding: 20px;
                max-width: 400px;
                width: 100%;
            }
        </style>
    @endsection

    <div class="container my-4">
        <div class="chat-container shadow-sm">
            {{-- Chat Header --}}
            <div class="chat-header">
                <a href="{{ route('conversations.index') }}" class="btn btn-link text-dark me-2 p-0">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div class="chat-header-avatar" data-user-id="{{ $otherUser->id }}">
                    <img src="{{ $otherUser->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) }}" alt="{{ $otherUser->name }}">
                    <span class="online-indicator {{ $otherUser->is_online ? 'online' : 'offline' }}"></span>
                </div>
                <div class="chat-header-info">
                    <h6>{{ $otherUser->name }} {{ $otherUser->prenom ?? '' }}</h6>
                    <small id="user-status">
                        @if($otherUser->is_online)
                            <span class="text-success"><i class="bi bi-circle-fill" style="font-size:8px"></i> En ligne</span>
                        @else
                            <span class="text-muted"><i class="bi bi-circle-fill" style="font-size:8px"></i> 
                                {{ $otherUser->last_seen_at ? 'Vu ' . $otherUser->last_seen_at->diffForHumans() : 'Hors ligne' }}
                            </span>
                        @endif
                    </small>
                </div>
            </div>

            {{-- Messages area --}}
            <div class="chat-messages" id="chat-messages">
                @php $lastDate = null; @endphp
                @foreach($messages as $msg)
                    @if($lastDate !== $msg->created_at->format('Y-m-d'))
                        @php $lastDate = $msg->created_at->format('Y-m-d'); @endphp
                        <div class="date-separator">
                            <span>{{ $msg->created_at->format('d M Y') }}</span>
                        </div>
                    @endif
                    
                    <div class="message-bubble {{ $msg->sender_id === auth()->id() ? 'message-sent' : 'message-received' }}" data-message-id="{{ $msg->id }}">
                        @if($msg->body)
                            <p class="message-text">{{ $msg->body }}</p>
                        @endif
                        @if($msg->hasFile())
                            <div class="message-file">
                                @if($msg->isImage())
                                    <img src="{{ asset('storage/' . $msg->file_path) }}" 
                                         alt="{{ $msg->file_name }}" 
                                         class="img-fluid"
                                         onclick="window.open(this.src, '_blank')">
                                @else
                                    <a href="{{ asset('storage/' . $msg->file_path) }}" 
                                       class="message-file-doc" 
                                       target="_blank" download>
                                        <i class="bi bi-file-earmark-arrow-down fs-5"></i>
                                        <div>
                                            <div class="fw-medium small">{{ $msg->file_name }}</div>
                                            <div style="font-size:11px;opacity:0.7">Télécharger</div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        @endif
                        <div class="message-time">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                @endforeach
            </div>

            {{-- File preview overlay --}}
            <div class="file-preview-overlay" id="file-preview">
                <div class="file-preview-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold">Envoyer un fichier</h6>
                        <button class="btn-close" onclick="cancelFile()"></button>
                    </div>
                    <div id="file-preview-content" class="text-center mb-3"></div>
                    <p class="small text-muted mb-3" id="file-preview-name"></p>
                    <form id="file-upload-form" method="POST" action="{{ route('messages.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                        <input type="file" name="file" id="file-input" class="d-none" 
                               accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv">
                        <input type="text" name="body" class="form-control form-control-sm mb-2" placeholder="Ajouter un message (optionnel)">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-send me-1"></i> Envoyer
                        </button>
                    </form>
                </div>
            </div>

            {{-- Chat input --}}
            <div class="chat-input-container">
                <form class="chat-input-form" id="message-form">
                    @csrf
                    <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                    <button type="button" class="chat-file-btn" onclick="document.getElementById('file-input').click()">
                        <i class="bi bi-paperclip fs-5"></i>
                    </button>
                    <input type="text" name="body" class="chat-input" id="message-input" 
                           placeholder="Tapez votre message..." autocomplete="off">
                    <button type="submit" class="chat-send-btn">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chat-messages');
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');
            const fileInput = document.getElementById('file-input');
            const filePreview = document.getElementById('file-preview');
            const conversationId = {{ $conversation->id }};
            const currentUserId = {{ auth()->id() }};

            // Scroll to bottom
            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            scrollToBottom();

            // Send text message via AJAX
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const body = messageInput.value.trim();
                if (!body) return;

                const formData = new FormData(messageForm);

                fetch('{{ route("messages.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        appendMessage(data.message, true);
                        messageInput.value = '';
                    }
                })
                .catch(err => console.error('Error sending:', err));
            });

            // File input change - show preview
            fileInput.addEventListener('change', function() {
                if (this.files.length === 0) return;
                const file = this.files[0];
                const previewContent = document.getElementById('file-preview-content');
                const previewName = document.getElementById('file-preview-name');

                previewName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContent.innerHTML = '<img src="' + e.target.result + '" class="img-fluid rounded" style="max-height:200px">';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContent.innerHTML = '<i class="bi bi-file-earmark fs-1 text-muted"></i>';
                }

                filePreview.style.display = 'flex';
            });

            // File upload form submit
            document.getElementById('file-upload-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch('{{ route("messages.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        appendMessage(data.message, true);
                        cancelFile();
                    }
                })
                .catch(err => console.error('Upload error:', err));
            });

            // Listen for incoming messages via Echo
            if (typeof window.Echo !== 'undefined') {
                window.Echo.private('conversation.' + conversationId)
                    .listen('.message.sent', (data) => {
                        appendMessage(data, false);
                        // Mark as read
                        fetch('/messages/' + conversationId + '/read', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            }
                        });
                    })
                    .listen('.message.read', (data) => {
                        // Messages have been read by the other user
                        document.querySelectorAll('.message-sent .message-read-status')
                            .forEach(el => el.innerHTML = '<i class="bi bi-check2-all text-info"></i>');
                    });

                // Listen for user status change
                window.Echo.channel('online')
                    .listen('.user.status', (data) => {
                        if (data.user_id === {{ $otherUser->id }}) {
                            const statusEl = document.getElementById('user-status');
                            const indicator = document.querySelector('.chat-header-avatar .online-indicator');
                            if (data.is_online) {
                                statusEl.innerHTML = '<span class="text-success"><i class="bi bi-circle-fill" style="font-size:8px"></i> En ligne</span>';
                                indicator?.classList.add('online');
                                indicator?.classList.remove('offline');
                            } else {
                                statusEl.innerHTML = '<span class="text-muted"><i class="bi bi-circle-fill" style="font-size:8px"></i> Hors ligne</span>';
                                indicator?.classList.remove('online');
                                indicator?.classList.add('offline');
                            }
                        }
                    });
            }

            // Append a message to the chat
            function appendMessage(msg, isSent) {
                const div = document.createElement('div');
                div.className = 'message-bubble ' + (isSent ? 'message-sent' : 'message-received');
                div.dataset.messageId = msg.id;

                let html = '';
                if (msg.body) {
                    html += '<p class="message-text">' + escapeHtml(msg.body) + '</p>';
                }
                if (msg.file_path) {
                    html += '<div class="message-file">';
                    if (msg.file_type && msg.file_type.startsWith('image/')) {
                        html += '<img src="/storage/' + msg.file_path + '" class="img-fluid" style="max-width:240px;border-radius:8px">';
                    } else {
                        html += '<a href="/storage/' + msg.file_path + '" class="message-file-doc" target="_blank" download>';
                        html += '<i class="bi bi-file-earmark-arrow-down fs-5"></i>';
                        html += '<div><div class="fw-medium small">' + escapeHtml(msg.file_name || 'Fichier') + '</div>';
                        html += '<div style="font-size:11px;opacity:0.7">Télécharger</div></div></a>';
                    }
                    html += '</div>';
                }
                const time = msg.created_at ? new Date(msg.created_at).toLocaleTimeString('fr-FR', {hour:'2-digit', minute:'2-digit'}) : '';
                html += '<div class="message-time">' + time + '</div>';

                div.innerHTML = html;
                chatMessages.appendChild(div);
                scrollToBottom();
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        });

        function cancelFile() {
            document.getElementById('file-preview').style.display = 'none';
            document.getElementById('file-input').value = '';
            document.getElementById('file-preview-content').innerHTML = '';
            document.getElementById('file-preview-name').textContent = '';
        }
    </script>
    @endsection
</x-master>

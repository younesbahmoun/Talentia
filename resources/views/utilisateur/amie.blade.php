<x-master title="Network">
    @section('styles')
        <style>
            .network-card {
                background: white;
                border-radius: 12px;
                padding: 20px;
                border: 1px solid #e9ecef;
            }

            .request-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 16px 0;
                border-bottom: 1px solid #f0f0f0;
            }

            .request-item:last-child {
                border-bottom: none;
            }

            .btn-primary-custom {
                background: #0d6efd;
                border: none;
            }

            .btn-primary-custom:hover {
                background: #0b5ed7;
            }

            .friend-avatar {
                position: relative;
                display: inline-block;
            }
        </style>
    @endsection
        
    <div class="container">
        <!-- Invitations -->
        <div class="network-card shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Invitations ({{ $invitations->count() }})</h5>
                <a href="{{ route('network') }}" class="text-decoration-none small fw-bold">Manage all</a>
            </div>

            @unless(!session()->has('success'))
                <x-alert :type="'success'">
                    <p>{{ session('success') }}</p>
                </x-alert>
            @endunless

            @if ($invitations->count() == 0)
                <p class="text-center">Aucune invitation</p>
            @else
                <!-- Requests-->
                @foreach ($invitations as $invitation)
                    <div class="request-item">
                        <div class="d-flex align-items-center">
                            <div class="friend-avatar me-3" data-user-id="{{ $invitation->user->id }}">
                                <img src="{{ $invitation->user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($invitation->user->name) }}"
                                    class="rounded-circle" width="56" height="56">
                                <span class="online-indicator {{ $invitation->user->is_online ? 'online' : 'offline' }}"></span>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $invitation->user->name }} {{ $invitation->user->prenom ?? '' }}</h6>
                                <p class="text-secondary small mb-1">{{ $invitation->user->email }}</p>
                                <small
                                    class="{{ $invitation->user->is_online ? 'text-success' : 'text-muted' }}"
                                    data-presence-text
                                    data-user-id="{{ $invitation->user->id }}">
                                    @if($invitation->user->is_online)
                                        <i class="bi bi-circle-fill" style="font-size:6px"></i> En ligne
                                    @elseif($invitation->user->last_seen_at)
                                        Vu {{ $invitation->user->last_seen_at->diffForHumans() }}
                                    @else
                                        Hors ligne
                                    @endif
                                </small>
                                <br>
                                @if($invitation->user->profile)
                                    <small class="text-muted"><i class="bi bi-briefcase-fill me-1"></i>{{ $invitation->user->profile->specialite ?? 'No speciality' }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('refuser.amie', ['friend_id' => $invitation->user_id]) }}">
                                <button class="btn btn-outline-secondary fw-semibold">Supprimer</button>
                            </a>
                            <a href="{{ route('accepter.amie', ['friend_id' => $invitation->user_id]) }}">
                                <button class="btn btn-primary-custom text-white">Confirmer</button>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- My Connections -->
        <div class="network-card shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">My Network</h5>
                <div class="input-group w-50">
                    <span class="input-group-text bg-white border-end-0"><i
                            class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Search my connections...">
                </div>
            </div>
            <div class="list-group list-group-flush">
                @if($friends->count() == 0)
                    <p class="text-center">Aucune ami</p>
                @else
                    @foreach($friends as $friend)
                        @php
                            $friendUser = $friend->user_id === auth()->id() ? $friend->friend : $friend->user;
                        @endphp
                        <div class="list-group-item px-0 py-3 border-bottom d-flex align-items-center">
                            <div class="friend-avatar me-3" data-user-id="{{ $friendUser->id }}">
                                <img src="{{ $friendUser->photo }}"
                                    class="rounded-circle" width="56" height="56">
                                <span class="online-indicator {{ $friendUser->is_online ? 'online' : 'offline' }}"></span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0">{{ $friendUser->name }} {{ $friendUser->prenom ?? '' }}</h6>
                                <p class="text-secondary small mb-0">{{ $friendUser->email }}</p>
                                <small
                                    class="{{ $friendUser->is_online ? 'text-success' : 'text-muted' }}"
                                    data-presence-text
                                    data-user-id="{{ $friendUser->id }}">
                                    @if($friendUser->is_online)
                                        <i class="bi bi-circle-fill" style="font-size:6px"></i> En ligne
                                    @elseif($friendUser->last_seen_at)
                                        Vu {{ $friendUser->last_seen_at->diffForHumans() }}
                                    @else
                                        Hors ligne
                                    @endif
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('conversations.store') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $friendUser->id }}">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-chat me-1"></i>Message
                                    </button>
                                </form>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('profile.detail', $friendUser->id) }}">
                                                <i class="bi bi-person me-2"></i>Voir le profil
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="bi bi-person-dash me-2"></i>Remove connection
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="text-center mt-3">
                <button class="btn btn-outline-secondary btn-sm">Load more</button>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('talentia:user-status-changed', (event) => {
                const data = event.detail || {};
                const userId = Number(data.user_id || 0);

                if (!userId) {
                    return;
                }

                document.querySelectorAll('[data-presence-text][data-user-id="' + userId + '"]')
                    .forEach((element) => {
                        if (data.is_online) {
                            element.classList.remove('text-muted');
                            element.classList.add('text-success');
                            element.innerHTML = '<i class="bi bi-circle-fill" style="font-size:6px"></i> En ligne';
                            return;
                        }

                        element.classList.remove('text-success');
                        element.classList.add('text-muted');
                        element.textContent = formatOfflineText(data.last_seen_at);
                    });
            });

            function formatOfflineText(lastSeenAt) {
                if (!lastSeenAt) {
                    return 'Hors ligne';
                }

                const date = new Date(lastSeenAt);
                if (Number.isNaN(date.getTime())) {
                    return 'Hors ligne';
                }

                return 'Vu a ' + date.toLocaleTimeString('fr-FR', {
                    hour: '2-digit',
                    minute: '2-digit',
                });
            }
        });
    </script>
    @endsection
</x-master>

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
                            <img src="{{ $invitation->friend->photo }}"
                                class="rounded-circle me-3" width="56" height="56">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $invitation->friend->name }}</h6>
                                <p class="text-secondary small mb-1">{{ $invitation->friend->email }}</p>
                                <small class="text-muted"><i class="bi bi-people-fill me-1"></i> 3 mutual
                                    connections</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('refuser.amie', ['friend_id' => $friend->friend->id]) }}">
                                <button class="btn btn-outline-secondary fw-semibold">Supprimer</button>
                            </a>
                            <a href="{{ route('accepter.amie', ['friend_id' => $friend->friend->id]) }}">
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
                @foreach($friends as $friend)
                    <!-- Connection 1 -->
                    <div class="list-group-item px-0 py-3 border-bottom d-flex align-items-center">
                        <img src="{{ $friend->friend->photo }}"
                            class="rounded-circle me-3" width="56" height="56">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0">{{ $friend->friend->name }}</h6>
                            <p class="text-secondary small mb-0">{{ $friend->friend->email }}</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown"><i
                                    class="bi bi-three-dots-vertical"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <iclass="bi bi-chat me-2">
                                        </i>Message</a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <iclass="bi bi-person-dash me-2">
                                        </i>Remove connection</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-3">
                <button class="btn btn-outline-secondary btn-sm">Load more</button>
            </div>
        </div>
    </div>
</x-master>
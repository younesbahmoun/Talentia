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
        
    <!-- Invitations -->
     <div class="container">
    <div class="network-card shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Invitations ({{ $friends->count() }})</h5>
            <a href="#" class="text-decoration-none small fw-bold">Manage all</a>
        </div>

        <!-- Requests-->
        @foreach ($friends as $friend)
            <div class="request-item">
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name={{ $friend->friend->name }}&background=random"
                        class="rounded-circle me-3" width="56" height="56">
                    <div>
                        <h6 class="fw-bold mb-0">{{ $friend->friend->name }}</h6>
                        <p class="text-secondary small mb-1">{{ $friend->friend->email }}</p>
                        <small class="text-muted"><i class="bi bi-people-fill me-1"></i> 3 mutual
                            connections</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="">
                        <button class="btn btn-outline-secondary fw-semibold">Supprimer</button>
                    </a>
                    <a href="">
                        <button class="btn btn-primary-custom text-white">Confirmer</button>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</x-master>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('components.navbar')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- Photo & Name -->
                        <div class="d-flex align-items-center mb-4">
                            <img src="{{ $user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name . ' ' . $user->prenom) . '&background=0D8ABC&color=fff&size=80' }}" 
                                 class="rounded-circle me-3" 
                                 alt="Photo"
                                 width="80"
                                 height="80"
                                 style="object-fit: cover;">
                            <div>
                                <h4>{{ $user->name }} {{ $user->prenom }}</h4>
                                <p class="text-muted mb-0">{{ $user->specialite }}</p>
                            </div>
                        </div>

                        <!-- Fields -->
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="text" class="form-control" value="{{ $user->email }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Spécialité</label>
                            <input type="text" class="form-control" value="{{ $user->specialite }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Bio</label>
                            <textarea class="form-control" rows="3" readonly>{{ $user->bio }}</textarea>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Modifier</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
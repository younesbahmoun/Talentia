<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- @php $currentPage = 'profile'; @endphp -->
    @include('components.navbar')
    <!-- Profile Section -->
    <div class="container mt-5">
        <h1>Modifier le profil</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Mon Profil</h2>
                        <!-- Photo de profil -->
                        <img src="{{ $user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name . ' ' . $user->prenom) . '&background=0D8ABC&color=fff&size=150' }}" alt="Photo profil de {{ $user->name }} {{ $user->prenom }}" class="img-fluid rounded-circle mb-3 d-block mx-auto" width="150" height="150">

                        <!-- Formulaire de profil -->
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Nom -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <!-- Prénom -->
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" name="prenom" class="form-control" id="prenom" value="{{ old('prenom', $user->prenom) }}">
                            </div>

                            <!-- Spécialité / Entreprise -->
                            <div class="mb-3">
                                <label for="specialite" class="form-label">Spécialité / Entreprise</label>
                                <input type="text" name="specialite" class="form-control" id="specialite" value="{{ old('specialite', $user->specialite) }}">
                            </div>

                            <!-- Bio -->
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea name="bio" class="form-control" id="bio" rows="4">{{ old('bio', $user->bio) }}</textarea>
                            </div>

                            <!-- Photo de profil -->
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" name="photo" class="form-control" id="photo" accept="image/*">
                            </div>

                            <!-- Bouton de sauvegarde -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('profile.show') }}" class="btn btn-secondary">Retour au profil</a>
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
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
    <h1>Modifier le profil</h1>
    <!-- Profile Section -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Mon Profil</h2>
                        <img src="{{ $user->photo }}" alt="Photo profil de {{ $user->name }} {{ $user->prenom }}" class="img-fluid rounded-circle mb-3 d-block mx-auto" width="150" height="150">
                        
                        <!-- Photo de profil -->
                        <!-- <div class="text-center mb-4">
                            <img src="https://via.placeholder.com/150" class="rounded-circle" alt="Photo de profil" width="150" height="150">
                            <div class="mt-3">
                                <button class="btn btn-sm btn-outline-primary">Changer la photo</button>
                            </div>
                        </div> -->

                        <!-- Formulaire de profil -->
                        <form action="{{ route('profile.show') }}" method="POST">
                            <!-- Nom -->
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" value="{{ $user->name }}">
                            </div>

                            <!-- Spécialité / Entreprise -->
                            <div class="mb-3">
                                <label for="specialite" class="form-label">Spécialité / Entreprise</label>
                                <input type="text" class="form-control" id="specialite" value="{{ $user->specialite }}">
                            </div>

                            <!-- Bio -->
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" rows="4">{{ $user->photo }}</textarea>
                            </div>

                            <!-- Photo de profil -->
                            <div class="mb-3">
                                <label for="nom" class="form-label">Photo</label>
                                <input type="text" class="form-control" id="nom" value="{{ $user->photo }}">
                            </div>

                            <!-- Bouton de sauvegarde -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('profile.show') }}" class="btn btn-primary">Retour au profil</a>
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
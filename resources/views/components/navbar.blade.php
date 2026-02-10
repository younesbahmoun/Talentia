<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        {{-- <a class="navbar-brand" href="{{ route('home') }}">Espace Emploi</a> --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    {{-- <a class="nav-link" href="{{ route('home') }}">Accueil</a> --}}
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('recherche.index') }}">Recherche</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.show') }}">Mon profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profiles.all') }}">All profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('network') }}">Network</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('offres.index') }}">Offres</a>
                    </li>
                    @role('recruteur')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('recruiter.candidats') }}">Mes Candidats</a>
                        </li>
                    @endrole
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('notifications') }}">
                            Notifications
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </a>
                    </li>
                @endauth
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-white">Déconnexion</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

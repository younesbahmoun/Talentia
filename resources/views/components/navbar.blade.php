<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <i class="bi bi-briefcase-fill me-1"></i> Talentia
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
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
                @endauth
            </ul>
            <ul class="navbar-nav">
                @auth
                    {{-- Messages link with unread badge --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('conversations.index') }}">
                            <i class="bi bi-chat-dots-fill fs-5"></i>
                            <span id="message-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="{{ auth()->user()->totalUnreadMessages() > 0 ? '' : 'display: none;' }}">
                                {{ auth()->user()->totalUnreadMessages() > 0 ? auth()->user()->totalUnreadMessages() : '' }}
                            </span>
                        </a>
                    </li>

                    {{-- Notifications link with unread badge --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('notifications') }}">
                            <i class="bi bi-bell-fill fs-5"></i>
                            <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="{{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'display: none;' }}">
                                {{ auth()->user()->unreadNotifications->count() > 0 ? auth()->user()->unreadNotifications->count() : '' }}
                            </span>
                        </a>
                    </li>

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

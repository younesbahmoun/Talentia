<x-master title="Offres d'emploi">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Offres d'emploi</h1>
            @auth
                @role('recruteur')
                    <a href="{{ route('offres.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter une offre
                    </a>
                @endrole
            @endauth
        </div>
        <div class="row g-4">
            @if($offres->count() > 0)
                @foreach($offres as $offre)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title">{{ $offre->titre }}</h5>
                                    @if($offre->status == 'ouvert')
                                        <span class="badge bg-success">Ouvert</span>
                                    @else
                                        <span class="badge bg-secondary">Fermé</span>
                                    @endif
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $offre->entreprise }}</h6>
                                <span class="badge bg-primary mb-3">{{ $offre->type_contrat }}</span>
                                <p class="card-text">{{ Str::limit($offre->description, 30) }}</p>
                                <a href="{{ route('offres.detail', $offre->id) }}" class="btn btn-primary">Voir détails</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Aucun Offres</p>
            @endif
        </div>
    </div>
</x-master>
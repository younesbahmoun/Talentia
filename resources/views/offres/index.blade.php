<x-master title="Offres d'emploi">
    <div class="container my-5">
        <div class="row g-4">
            
            @if($offres->count() > 0)
                @foreach($offres as $offre)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <!-- <img src="https://intranet.youcode.ma/storage/users/profile/thumbnail/1873-1760996499.png" class="card-img-top" alt="Image offre"> -->
                            <div class="card-body">
                                <h5 class="card-title">{{ $offre->titre }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $offre->entreprise }}</h6>
                                <span class="badge bg-primary mb-3">{{ $offre->type_contrat }}</span>
                                <p class="card-text">{{ Str::limit($offre->description, 30) }}</p>
                                <a href="#" class="btn btn-primary">Voir détails</a>
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
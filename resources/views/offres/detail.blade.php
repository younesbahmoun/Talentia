@section('footer')
    @include('components.footer')
@endsection
<x-master title="Détails de l'offre">
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                
                <!-- Image de l'offre -->
                @unless(!$offres->image)
                    <img src="{{ $offres->image }}" class="img-fluid rounded mb-4" alt="Image offre">
                @endunless
                
                <!-- En-tête de l'offre -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="mb-2">{{ $offres->titre }}</h1>
                        <h5 class="text-muted">{{ $offres->entreprise }}</h5>
                    </div>
                    <span class="badge bg-primary fs-6">{{ $offres->type_contrat }}</span>
                </div>

                <hr>

                <!-- Description -->
                <div class="mb-4">
                    <h3 class="mb-3">Description du poste</h3>
                    <p class="lead">{{ $offres->description }}</p>
                    <!-- <p>
                        Duis aute irure dolor in reprehenderit in voluptate velit esse 
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat 
                        cupidatat non proident, sunt in culpa qui officia deserunt mollit 
                        anim id est laborum.
                    </p> -->
                </div>

                <!-- Bouton Postuler -->
                <div class="d-grid gap-2 d-md-block">
                    <a href="#" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-send-fill"></i> Postuler à cette offre
                    </a>
                    <a href="index.html" class="btn btn-outline-secondary btn-lg">
                        Retour aux offres
                    </a>
                </div>

            </div>
        </div>
    </div>
   
</x-master>
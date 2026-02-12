<x-master title="Candidats">
    <div class="container my-5">
        <h1 class="mb-4">Mes Candidats</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($offres->count() > 0)
            @foreach($offres as $offre)
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>{{ $offre->titre }}</h3>
                        <p class="text-muted mb-0">{{ $offre->entreprise }} - {{ $offre->type_contrat }}</p>
                    </div>
                    <div class="card-body">
                        @if($offre->applications->count() > 0)
                            <h5>Candidatures ({{ $offre->applications->count() }})</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Statut</th>
                                            <th>Date de candidature</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($offre->applications as $application)
                                            <tr>
                                                <td>{{ $application->user->name }} {{ $application->user->prenom }}</td>
                                                <td>{{ $application->user->email }}</td>
                                                <td>
                                                    @if($application->status == 'pending')
                                                        <span class="badge bg-warning">En attente</span>
                                                    @elseif($application->status == 'accepted')
                                                        <span class="badge bg-success">Accepté</span>
                                                    @else
                                                        <span class="badge bg-danger">Rejeté</span>
                                                    @endif
                                                </td>
                                                <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('profile.detail', $application->user->id) }}" class="btn btn-sm btn-primary">Voir profil</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Aucune candidature pour cette offre.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                Vous n'avez pas encore créé d'offres. <a href="{{ route('offres.create') }}">Créer une offre</a>
            </div>
        @endif
    </div>
</x-master>

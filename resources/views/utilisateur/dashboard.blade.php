<x-master title="Utilisateur Dashboard">
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">✓ Succès!</h4>
                <p>{{ session('success') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">✗ Erreur!</h4>
                <p>{{ session('error') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <h1> Welcome, {{ Auth::user()->name }} {{ Auth::user()->prenom }}</h1>
        <p>This is a simple Bootstrap navigation template.</p>
    </div>
    @section('footer')
        @include('components.footer')
    @endsection
</x-master>
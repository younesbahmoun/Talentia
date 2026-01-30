<?php
// echo "<pre>";
// print_r($user);
// echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Nav Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('components.navbar')
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
    </div>

    <!-- Main Content -->
    <div class="container mt-4">
        <h1> Welcome, {{ Auth::user()->name }} {{ Auth::user()->prenom }}</h1>
        <p>This is a simple Bootstrap navigation template.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
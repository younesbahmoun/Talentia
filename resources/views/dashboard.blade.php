<?php
// echo "<pre>";
// print_r(Auth::user());
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
    @php $currentPage = 'dashboard'; @endphp
    @include('components.navbar')

    <!-- Main Content -->
    <div class="container mt-4">
        <h1>Welcome to Your Dashboard</h1>
        <p>This is a simple Bootstrap navigation template.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>